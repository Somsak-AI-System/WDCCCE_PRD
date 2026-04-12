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
        <div class="top-nav-back-icon" onclick="Javascript:window.location.href = '<?php echo site_url('Quotes/create?userid=' .$this->session->userdata('userID'). '/back'); ?>';"><i class="ph-caret-left"></i></div>
        <span>ข้อมูลใบเสนอบริการ</span>
        <div class="top-nav-action flex">
            <div class="top-nav-action-icon flex-none cursor-pointer" onclick="$.submitForm()"><i class="ph-caret-right"></i></div>
        </div>
    </div>
</div>
<!-- End Top Navbar -->

<!-- Page Content -->
<div class="page-content mt-48">
    <form id="form-quotes" method="post" action="">
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
    function getPopupList(moduleSelect, fieldID, filter, selectfield, Page, relate_field_up, relate_field_down){
        var params = {moduleSelect, offSet}
        var field_down = ''
        var field_up = ''
        if(filter !== undefined) params.filter = filter
        
        if(relate_field_up !== undefined && relate_field_up !== ''){
            params.relate_field_up = $(`#${relate_field_up}`).val()
            field_up = relate_field_up
        }
        
        if(relate_field_down !== undefined && relate_field_down !== ''){
            var field_down = relate_field_down
        }

        $.post('<?php echo site_url('Quotes/getPopupList'); ?>', params, function(rs){
            //$(`#list-${moduleSelect}`).html('')
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
                    $.setPopupValue(fieldID, item, field_up, field_down)
                })
                
                //$(`#list-${moduleSelect}`).append(rowItem)
                $(`#list-${moduleSelect}-${fieldID}`).append(rowItem)
            })
        },'json')
    }

    function getPopupListEvent(moduleSelect, fieldID, filter, selectfield, Page, relate_field_up, relate_field_down){
        var params = {moduleSelect, offSet}
        var field_down = ''
        var field_up = ''
        if(filter !== undefined) params.filter = filter

        if(relate_field_up !== undefined && relate_field_up !== ''){
            params.relate_field_up = $(`#${relate_field_up}`).val()
            field_up = relate_field_up
        }
        
        if(relate_field_down !== undefined && relate_field_down !== ''){
            var field_down = relate_field_down
        }

        $.post('<?php echo site_url('Quotes/getPopupList'); ?>', params, function(rs){
            
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
                    $.setPopupValue(fieldID, item, field_up, field_down)
                })
                
                $(`#list-${fieldID}`).append(rowItem)
            })
        },'json')
    }

    $(function(){
        var myDate = new Date();
        var date = ('0'+ myDate.getDate()).slice(-2) + '/' +  ('0'+ (myDate.getMonth()+1)).slice(-2) + '/' + myDate.getFullYear() ;
        $("#quotation_date").val(date);

        var closedate = new Date();
        closedate.setDate(closedate.getDate()+7);
        var date_c = ('31'+ '/' + '12' + '/' + closedate.getFullYear()) ;
        $("#quotation_enddate").val(date_c);

        $("select[name='quotation_buyer']").change(function() {
            var quotation_buyer = $(this).val();
            var params = {quotation_buyer}

            if(quotation_buyer == '--None--'){
                $("#buyer_address").val('');
                $("#buyer_phone").val('');
                $("#buyer_email").val('');

            }else{
                $.post('<?php echo site_url('Quotes/Get_Buyer'); ?>', params, function(rs){
                    if(rs.status === 'Success'){
                        $("#buyer_address").val(rs.data.address);
                        $("#buyer_phone").val(rs.data.phone);
                        $("#buyer_email").val(rs.data.email);
                    } else {
                        $("#buyer_address").val('');
                        $("#buyer_phone").val('');
                        $("#buyer_email").val('');
                    }
                },'json')
            }
        });
        
        $('.check-assign').change(function(i, e){
            const eleID = $('input[name="assign_to"]:checked').attr('id');
            $('.check-assign-list').hide();
            $(`#${eleID}-list`).show()
        })

        $('.input-popup-search').keyup(function(i, e){
            var len = $(this).val().length
            var moduleSelect = $(this).data('moduleselect')
            var fieldID = $(this).data('field')
            
            var relate_field_down = $(this).data('relate-field-down')
            var relate_field_up = $(this).data('relate-field-up')
            
            if(len === 0 || len > 2){
                offSet = 0
                getPopupList(moduleSelect, fieldID, $(this).val(), '', '1', relate_field_up, relate_field_down)
            }
        })

        $('.input-popup-search-event').keyup(function(i, e){
            var len = $(this).val().length
            //var moduleSelect = $(this).data('moduleselect')
            var moduleSelect = $('#event_type').val();
            var fieldID = $(this).data('field')
            if(len === 0 || len > 2){
                offSet = 0
                getPopupListEvent(moduleSelect, fieldID, $(this).val())
            }
        })

        $( "#event_type" ).on( "change", function(i,e) {
            //console.log(this.value);
            $('#event_id-modal-search-box').attr('data-moduleselect', this.value);
            $('#event_id-input').val('');
            $('#event_id').val('');
        } );

        $.submitForm = function(){
            $('#form-quotes').submit();
        }

        $('#form-quotes').submit(function(e) {
            console.log('form submit')
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
            $.post('<?php echo site_url('Quotes/save?userid='.$this->session->userdata('userID')); ?>', formData, function(rs){
                // console.log(rs)
                if(rs.status === 'Success'){
                    window.location.href = `<?php echo site_url('Quotes/createProduct'); ?>/${rs.data.Crmid}?userid=${rs.userID}`
                } else {
                    $('.overlay').hide();
                    Swal.fire('', rs.message, 'error')
                }
            },'json')
        })
    })
</script>