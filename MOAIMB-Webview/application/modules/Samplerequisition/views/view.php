<div class="overlay">
    <div>
        <div class="loadingio-spinner-ripple">
            <div class="ldio-animate">
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="page-content mb-65">
    <?php if (is_array($blocks)) {
        foreach ($blocks as $index => $block) { ?>
            <div class="card-box mb-10">
                <div class="card-box-header flex">
                    <div class="card-box-title flex-1">
                        <?php echo $block['header_name']; ?>
                    </div>
                    <div class="card-box-action flex-none">
                        <div data-bs-toggle="collapse" href="#box<?php echo $index; ?>" role="button" aria-expanded="false">
                            <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="box<?php echo $index; ?>">
                    <div class="card-box-body">
                        <?php foreach ($block['form'] as $field) {
                            $field['module'] = $module;
                        ?>
                            <?php echo inputView($field); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
    <?php }
    } ?>

    <div class="card-box mb-10">
        <div class="card-box-header flex">
            <div class="card-box-title flex-1">
                Product Information
            </div>
            <div class="card-box-action flex-none">
                <div data-bs-toggle="collapse" href="#box4" role="button" aria-expanded="false">
                    <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                </div>
            </div>
        </div>
        <div class="collapse show" id="box4">
            <div class="card-box-body">
                <div class="font-16 text-gray-1">รายการ</div>
                <?php if (isset($priceInfo['itemList'])) {
                    foreach ($priceInfo['itemList'] as $item) {
                ?>
                        <div class="flex width-full list-item-row p-5 mb-5" onclick="$.showAddItem()">
                            <div class="flex-none">
                                <div class="list-item-icon bg-blue-1">
                                    <i class="ph-heart v-align-middle"></i>
                                </div>
                            </div>
                            <div class="flex-1 pl-10">
                                <div class="flex width-full">
                                    <div class="flex-1">
                                        <div class="font-16 font-bold text-line-clamp-1"><?php echo $item['name']; ?></div>
                                    </div>
                                    <div class="flex-none">
                                        <?php if ($item['amount'] != '0') echo $item['amount'] . ' ชิ้น' ?>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="flex-1">
                                        <div class="font-14 text-gray-4 text-line-clamp-1"><?php echo $item['no']; ?></div>
                                    </div>
                                    <!-- <div class="flex-none text-gray-1 text-right">
                                        ฿ <?php //echo $item['price_display']; ?>
                                    </div> -->
                                </div>
                                <div class="font-14 text-gray-4"><?php echo $item['product_finish']; ?> <?php echo $item['product_size_mm']; ?> <?php echo $item['product_thinkness']; ?></div>

                                <div class="font-14 text-gray-4"><label class="text-black-1">หมายเหตุ :</label> <?php echo $item['remark']; ?></div>
                            </div>
                        </div>
                <?php }
                } ?>

                <div class="devider"></div>

                <div class="flex">
                    <div class="flex-1">
                        จำนวน (แผ่น)
                    </div>
                    <div class="flex-none text-right width-150">
                        <?php echo $priceInfo['grandTotal']; ?> แผ่น
                    </div>
                </div>
                <div class="flex">
                    <div class="flex-1">
                        จำนวนที่คาดว่าจะใช้
                    </div>
                    <div class="flex-none text-right width-150">
                        <?php echo $priceInfo['grandTotalUnit']; ?> แผ่น
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- End Page Content -->

<div class="bottom-action">
    <?php if (in_array($recordData['samplerequisition_status'], ['Create', 'Request for Approve'])) { ?>
        <div class="flex-1 text-center py-10 cursor-pointer" id="btn-open-approve" onclick="$.showApprove();">
            <i class="ph-notification font-32"></i>
            <div class="text-center font-14 mt--5">อนุมัติ</div>
        </div>
    <?php } ?>
    <!-- <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.showPreview(<?php echo $crmID; ?>)">
        <i class="ph-file-text font-32"></i>
        <div class="text-center font-14 mt--5">รายงาน</div>
    </div> -->
    <?php if ($recordData['samplerequisition_status'] == 'Create') { ?>
        <!-- <div class="flex-1 text-center py-10 cursor-pointer" onclick="location.href='<?php echo site_url('Samplerequisition/createProduct/' . $crmID . '?userid=' . $userID); ?>'">
            <i class="ph-pencil-line font-32"></i>
            <div class="text-center font-14 mt--5">แก้ไข</div>
        </div> -->
        <div class="flex-1 text-center py-10 cursor-pointer" onclick="location.href='<?php echo site_url('Samplerequisition/edit/' . $crmID . '?userid=' . $userID); ?>'">
            <i class="ph-pencil-line font-32"></i>
            <div class="text-center font-14 mt--5">แก้ไข</div>
        </div>
    <?php } ?>
    <?php if ($recordData['samplerequisition_status'] == 'Rejected') { ?>
        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.revise('revise');">
            <i class="ph-arrows-clockwise font-32"></i>
            <div class="text-center font-14 mt--5">เปลี่ยนแปลง</div>
        </div>
    <?php } ?>
    <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.revise('duplicate');">
        <i class="ph-copy font-32"></i>
        <div class="text-center font-14 mt--5">ทำซ้ำ</div>
    </div>
    <?php if ($recordData['samplerequisition_status'] == 'Create') { ?>
        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.deleteRecord()">
            <i class="ph-trash font-32"></i>
            <div class="text-center font-14 mt--5">ลบ</div>
        </div>
    <?php } ?>
</div>

<div id="modal-approve" class="modal modal-bottom fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body bg-white p-0">
                <div class="flex">
                    <?php if (in_array($recordData['samplerequisition_status'], ['Create'])) { ?>
                        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.updateStatus('requestApprove');">
                            <i class="ph-file font-32"></i>
                            <div class="text-center font-14 mt--5">ขออนุมัติ</div>
                        </div>
                    <?php } ?>

                    <?php if (in_array($recordData['samplerequisition_status'], ['Request for Approve']) && $isApprover) { ?>
                        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.updateStatus('approve');">
                            <i class="ph-file-arrow-up font-32"></i>
                            <div class="text-center font-14 mt--5">อนุมัติ</div>
                        </div>
                    <?php } ?>

                    <?php if (in_array($recordData['samplerequisition_status'], ['Request for Approve']) && $isApprover) { ?>
                        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.updateStatus('reject');">
                        <i class="ph-file-x font-32"></i>
                            <div class="text-center font-14 mt--5">ไม่อนุมัติ</div>
                        </div>
                    <?php } ?>

                    <?php if (in_array($recordData['samplerequisition_status'], ['Create', 'Request for Approve'])) { ?>
                        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.updateStatus('cancel');">
                            <i class="ph-x-circle font-32"></i>
                            <div class="text-center font-14 mt--5">ยกเลิก</div>
                        </div>
                    <?php } ?>

                    <?php if (in_array($recordData['samplerequisition_status'], ['Approved', 'Rejected'])) { ?>
                        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.revise('revise');">
                            <i class="ph-arrows-clockwise font-32"></i>
                            <div class="text-center font-14 mt--5">เปลี่ยนแปลง</div>
                        </div>
                    <?php } ?>

                    <?php if (in_array($recordData['samplerequisition_status'], ['Approved'])) { ?>
                        <!-- <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.updateStatus('close');">
                            <i class="ph-check-circle font-32"></i>
                            <div class="text-center font-14 mt--5">ปิดการขาย</div>
                        </div> -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-approve-confirm" class="modal modal-bottom fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-20">
            <div class="modal-body bg-white">
                <div class="text-center mb-20">
                    <i class="ph-warning-circle text-primary font-70"></i>
                    <div id="approve-title"></div>
                </div>

                <div class="mb-20" id="row-reason">
                    <label class="pl-5 mb-5">เหตุผล</label>
                    <input type="hidden" name="samplerequisition_status" id="samplerequisition_status">
                    <textarea class="base-input" name="reason" id="reason" rows="5"></textarea>
                </div>

                <div class="mb-5">
                    <button type="button" class="btn btn-custom width-full" id="cancel-approve">ไม่ใช่</button>
                    <button type="button" class="btn btn-primary btn-custom width-full" id="confirm-approve">ใช่</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('form-preview'); ?>

<script>
    var crmID = '<?php echo $crmID; ?>'
    var userID = '<?php echo $userID; ?>'
    var url = '<?php echo $url; ?>'
    $(function() {
        setTimeout(function() {
            $('.overlay').hide();
        }, 1000)

        $.confirmStatus = function(status) {
            $.closeApproveStatus();
            Swal.fire('', 'Approve Conplete', 'success')
        }

        $.updateStatus = function(status) {
            $('#modal-approve').modal('hide');

            $('#samplerequisition_status').val(status);
            switch (status) {
                case 'requestApprove':
                    var title = 'คุณต้องการขออนุมัติใบขอตัวอย่างหรือไม่ ?'
                    $('#row-reason').hide();
                    break;
                case 'approve':
                    var title = 'คุณต้องการอนุมัติใบขอตัวอย่างหรือไม่ ?'
                    $('#row-reason').hide();
                    break;
                case 'reject':
                    var title = 'คุณต้องการไม่อนุมัติใบขอตัวอย่างหรือไม่ ?'
                    $('#row-reason').show();
                    break;
                case 'cancel':
                    var title = 'คุณต้องการยกเลิกใบขอตัวอย่างหรือไม่ ?'
                    $('#row-reason').show();
                    break;
                case 'change':
                    var title = 'คุณต้องการเปลี่ยนแปลงใบขอตัวอย่างหรือไม่ ?'
                    $('#row-reason').hide();
                    break;
                case 'close':
                    var title = 'คุณต้องการปิดใบขอตัวอย่างหรือไม่ ?'
                    $('#row-reason').hide();
                    break;
            }

            $('#approve-title').html(title)
            $('#modal-approve-confirm').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $('#cancel-approve').click(function() {
            $('#modal-approve-confirm').modal('hide');
            $('#samplerequisition_status').val('');
            $('#reason').val('');
        })

        $('#confirm-approve').click(function() {
            var status = $('#samplerequisition_status').val();
            switch (status) {
                case 'requestApprove':
                    $('.overlay').show();
                    $.requestApprove()
                    break;
                default:
                    $.setSamplerequisitionStatus(status)
                    break;
            }
        })

        $.closeApproveStatus = function() {
            $('#modal-approve-confirm').modal('hide');
        }

        $.closeApprove = function() {
            $('#modal-approve').modal('hide');
        }

        $.showApprove = function() {
            $('#modal-approve').modal('show');
        }

        $.closePreview = function() {
            $('#modal-preview').modal('hide');
        }

        $.showPreview = function() {
            window.location.href = '<?php echo site_url('Samplerequisition/viewReport/' . $crmID . '?userid=' . $userID . '&action=viewReport&watermark='.$watermark); ?>'
        }

        $.setSamplerequisitionStatus = function(status) {
            var reason = $('#reason').val();

            $.post('<?php echo site_url('Samplerequisition/updateStatus'); ?>', {
                crmID,
                status,
                reason
            }, function(rs) {
                $('.overlay').hide();
                if (rs.status) {
                    $('#modal-approve-confirm').modal('hide');
                    $('#reason').val('');

                    switch (status) {
                        case 'approve':
                            var alertTitle = 'อนุมัติใบขอตัวอย่างสำเร็จ'
                            break;
                        case 'reject':
                            var alertTitle = 'ไม่อนุมัติใบขอตัวอย่างสำเร็จ'
                            break;
                        case 'cancel':
                            var alertTitle = 'ยกเลิกใบขอตัวอย่างสำเร็จ'
                            break;
                        case 'change':
                            var alertTitle = 'เปลี่ยนแปลงใบขอตัวอย่างสำเร็จ'
                            break;
                        case 'close':
                            var alertTitle = 'ปิดใบขอตัวอย่างสำเร็จ'
                            break;
                    }
                    $('#btn-open-approve').hide();
                    Swal.fire('', alertTitle, 'success')

                    setTimeout(function() {
                        
                        window.location.href = '<?php echo site_url('Samplerequisition/view/' . $crmID . '?userid=' . $userID); ?>'
                    }, '1000')
                } else {
                    Swal.fire('', rs.msg, 'error')
                }
            }, 'json')
        }

        $.requestApprove = function() {
            $.post('<?php echo site_url('Samplerequisition/requestApprove'); ?>', {
                crmID
            }, function(rs) {
                $('.overlay').hide();
                if (rs.status) {
                    $('#modal-approve-confirm').modal('hide');
                    $('#reason').val('');
                    var alertTitle = 'ส่งอนุมัติใบขอตัวอย่างสำเร็จ'
                    Swal.fire('', alertTitle, 'success')
                    setTimeout(function() {
                        
                        window.location.href = '<?php echo site_url('Samplerequisition/view/' . $crmID . '?userid=' . $userID); ?>'
                    }, '1000')
                } else {
                    Swal.fire('', rs.msg, 'error')
                }
            }, 'json')
        }

        $.revise = function(status) {
            $('.overlay').show();
            $.post('<?php echo site_url('Samplerequisition/Revise'); ?>', {
                crmID,
                userID,
                status
            }, function(rs) {
                $('.overlay').hide();
                if (rs.status) {
                    
                    var url = '<?php echo site_url('Samplerequisition/edit/') ?>'+'/'+rs.crmID+ '?userID=' + rs.userID;
                    
                    $('#modal-approve-confirm').modal('hide');
                    $('#reason').val('');
                    var alertTitle = '';
                    if (status == 'revise') {
                        var alertTitle = 'เปลี่ยนแปลง ใบขอตัวอย่างสำเร็จ'
                    } else {
                        var alertTitle = 'Duplicate ใบขอตัวอย่างสำเร็จ'
                    }

                    Swal.fire('', alertTitle, 'success')
                    setTimeout(function() {
                        window.location.href = url;
                    }, '1000')
                } else {
                    Swal.fire('', rs.msg, 'error')
                }
            }, 'json')
        }

        $.deleteRecord = function() {
            Swal.fire({
                title: '',
                html: "คุณต้องการ<br/>ลบรายการนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#e97126">ไม่ใช่</span?',
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
    })
</script>