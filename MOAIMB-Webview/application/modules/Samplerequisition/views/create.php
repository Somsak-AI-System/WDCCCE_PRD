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
        <div class="top-nav-back-icon" onclick="Javascript:window.location.href = '<?php echo site_url('Samplerequisition/create?userid=' .$this->session->userdata('userID'). '/back'); ?>';"><i class="ph-caret-left"></i></div>
        <span>ข้อมูลใบขอตัวอย่าง</span>
        <div class="top-nav-action flex">
            <div class="top-nav-action-icon flex-none cursor-pointer" onclick="$.submitForm()"><i class="ph-caret-right"></i></div>
        </div>
    </div>
</div>
<!-- End Top Navbar -->

<!-- Page Content -->
<div class="page-content mt-48">
    <form id="form-samplerequisition" method="post" action="">
    <?php if(is_array($blocks)){ foreach($blocks as $index => $block) { ?>
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
                    <?php foreach($block['form'] as $field){ 
                        $field['module'] = $module;
                        ?>
                        <?php echo inputGroup($field); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php }} ?>
    </form>
</div>
<!-- End Page Content -->

<script>
    var offSet = 0;
    function getPopupList(moduleSelect, fieldID, filter){
        var params = {moduleSelect, offSet}
        if(filter !== undefined) params.filter = filter

        $.post('<?php echo site_url('Samplerequisition/getPopupList'); ?>', params, function(rs){
            
            $(`#list-${moduleSelect}-${fieldID}`).html('')
            rs.map(item => {
                var rowItem = $('<div />',{ class:`flex width-full list-item-popup-row px-20 py-5` })
                var rowHtml = `<div class="flex-none">
                    <div class="list-item-icon bg-${item.color}">
                        <i class="ph-${item.icon} v-align-middle"></i>
                    </div>
                </div>
                <div class="flex-1 pl-10 pt-5">
                    <div class="font-16 font-bold text-line-clamp-1">${item.name}</div>
                    <div class="font-16 text-line-clamp-1">${item.no}</div>
                </div>`;
                $(rowItem).html(rowHtml)
                $(rowItem).click(function(){
                    $.setPopupValue(fieldID, item)
                })
                $(`#list-${moduleSelect}-${fieldID}`).append(rowItem)
            })
        },'json')
    }

    function getPopupListEvent(moduleSelect, fieldID, filter){
        var params = {moduleSelect, offSet}
        if(filter !== undefined) params.filter = filter

        $.post('<?php echo site_url('Samplerequisition/getPopupList'); ?>', params, function(rs){
            
            $(`#list-${fieldID}`).html('')
            rs.map(item => {
                var rowItem = $('<div />',{ class:`flex width-full list-item-popup-row px-20 py-5` })
                var rowHtml = `<div class="flex-none">
                    <div class="list-item-icon bg-${item.color}">
                        <i class="ph-${item.icon} v-align-middle"></i>
                    </div>
                </div>
                <div class="flex-1 pl-10 pt-5">
                    <div class="font-16 font-bold text-line-clamp-1">${item.name}</div>
                    <div class="font-16 text-line-clamp-1">${item.no}</div>
                </div>`;
                $(rowItem).html(rowHtml)
                $(rowItem).click(function(){
                    $.setPopupValue(fieldID, item)
                })
                
                $(`#list-${fieldID}`).append(rowItem)
            })
        },'json')
    }

    $(function(){
        
        $('#to_mail').val('ดิษฐกาน ทิพวัลย์');
        $('#from_mail').val("<? echo $a_user['first_name_th'].' '.$a_user['last_name_th'] ;?>");
        $('#position').val("<? echo $a_user['position'] ;?>");

        $('.check-assign').change(function(i, e){
            const eleID = $('input[name="assign_to"]:checked').attr('id');
            $('.check-assign-list').hide();
            $(`#${eleID}-list`).show()
        })

        $('.input-popup-search').keyup(function(i, e){
            var len = $(this).val().length
            var moduleSelect = $(this).data('moduleselect')
            var fieldID = $(this).data('field')
            
            if(len === 0 || len > 2){
                offSet = 0
                getPopupList(moduleSelect, fieldID, $(this).val())
            }
        })

        $('.input-popup-search-event').keyup(function(i, e){
            var len = $(this).val().length
            var moduleSelect = $('#event_type').val();
            var fieldID = $(this).data('field')
            if(len === 0 || len > 2){
                offSet = 0
                getPopupListEvent(moduleSelect, fieldID, $(this).val())
            }
        })

        $( "#event_type" ).on( "change", function(i,e) {
            $('#event_id-modal-search-box').attr('data-moduleselect', this.value);
            $('#event_id-input').val('');
            $('#event_id').val('');
        } );

        $.submitForm = function(){
            $('#form-samplerequisition').submit();
        }

        $('#form-samplerequisition').submit(function(e){
            e.preventDefault();
            var form = $(this);
            var formData = form.serializeObject()

            for (var key in formData){
                var required = $(`#${key}`).prop('required')
                var fieldLabel = $(`#label-${key}`).html()
                var fieldValue = formData[key]

                $(`#${key}`).removeClass('input-error')
                if(required && (fieldValue==='' || fieldValue==='--None--')){
                    $(`#${key}`).addClass('input-error').focus()
                    $('html, body').animate({ scrollTop: $(`#${key}`).offset().top - 100 }, 500);
                    Swal.fire('', `${fieldLabel} is required`, 'warning')
                    return false;
                }
            }

            formData.action = 'add'
            $('.overlay').show();
            $.post('<?php echo site_url('Samplerequisition/save?userid='.$this->session->userdata('userID')); ?>', formData, function(rs){
                if(rs.status === 'Success'){
                    window.location.href = `<?php echo site_url('Samplerequisition/createProduct'); ?>/${rs.data.Crmid}?userid=${rs.userID}`
                } else {
                    $('.overlay').hide();
                    Swal.fire('', rs.message, 'error')
                }
            },'json')
        })
    })
</script>