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

<div class="top-nav">
    <div class="top-nav-content">
        <div class="top-nav-back-icon"><i class="ph-caret-left"></i></div>
        <span>บัตรกำนัล</span>
    </div>
</div>
<div class="top-bar">
    <div class="flex width-full">
        <div class="flex-none">
            <div class="top-bar-icon">
                <i class="ph-ticket"></i>
            </div>
        </div>
        <div class="flex-1 pl-10">
            <div class="font-16 font-bold text-line-clamp-1">บัตรกำนัล</div>
            <div class="font-12 text-gray-1 text-line-clamp-1">ACC65-0001</div>
            <div class="font-12 text-gray-2">บัตรกำนัล <span>1</span> รายการ</div>
        </div>
    </div>
</div>

<div class="item-list">
    <?php for ($i = 1; $i <= 20; $i++) { ?>
        <div class="item-row">
            <div class="flex">
                <div class="flex-1">
                    <div class="font-16 font-bold text-line-clamp-1">บัตรกำนัล</div>
                    <div class="font-12 text-gray-1 text-line-clamp-1">ACC65-0001</div>
                </div>
                <div class="flex-none">
                    <button type="button" class="btn btn-icon-default"><i class="ph-link"></i></button>
                    <button type="button" class="btn btn-icon-default" onclick="$.delete()"><i class="ph-trash"></i></button>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="bottom-bar shadow-top">
    <div class="width-full text-center cursor-pointer" onclick="$.searchShow()">
        <div><i class="ph-arrow-square-in font-38"></i></div>
        <div class="font-14">เพิ่มรายการ</div>
    </div>
</div>

<!-- ============================ Modal ======================== -->
<div class="modal modal-bottom fade" id="bottom_modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="flex">
                        <div class="flex-none px-5" style="padding-top:3px" onclick="$.closeModal();">
                            <i class="ph-caret-left font-18"></i>
                        </div>
                        <div class="flex-1 pl-5">
                            รายการบัตรกำนัล
                        </div>
                    </div>                    
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18" onclick="$.closeModal();"></i>
                </span>
            </div>
            <div class="modal-body modal-body-bottom">
                <div class="row pb-10 modal-sticky-top">
                    <div class="col">
                        <div class="search-box">
                            <input type="text" class="search-textbox" placeholder="ค้นหารายการบัตรกำนัล" />
                            <i class="ph-magnifying-glass search-box-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="search-result" id="search-result-list">
                    <?php for($i=1; $i<=30; $i++){ ?>
                        <div class="search-result-item">
                            <div class="font-16 font-bold text-line-clamp-1">บัตรกำนัล</div>
                            <div class="font-12 text-gray-1 text-line-clamp-1">ACC65-0001</div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        setTimeout(function() {
            $('.overlay').hide();
        }, 1000)

        $.delete = function() {
            Swal.fire({
                title: '',
                text: 'คุณต้องการลบรายการนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#e97126">ไม่ใช่</span?',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('', 'ลบข้อมูลสำเร็จ', 'success')
                }
            })
        }

        $.searchShow = function() {
            $('#bottom_modal').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }

        $.closeModal = function(){
            $('#bottom_modal').modal('hide');
        }
    })
</script>