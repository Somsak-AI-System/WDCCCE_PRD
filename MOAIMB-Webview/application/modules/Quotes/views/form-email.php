<div id="modal-form-email" class="modal modal-bottom fade" role="dialog" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <!--Content-->
        <div class="modal-content" style="height:550px; overflow-y: scroll">
            <!--Body-->
            <div class="modal-body mb-0 p-0">
                <div class="embed-responsive z-depth-1-half form-email" style="height:100%">
                    <form id="form-email" method="post" action="">
                        <div class="card-box mb-10">
                            <div class="card-box-header flex">
                                <input type="hidden" name="crmid" id="crmid" value="<?php echo $crmid; ?>" />
                                <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>" />
                                <input type="hidden" name="save_type" value="form" />
                                <input type="hidden" id="CountRowsID" value="0" />
                                <div class="card-box-title flex-1">
                                    ส่งอีเมล </div>
                                <div class="card-box-action flex-none">

                                </div>
                            </div>
                            <div class="collapse show">
                                <div class="card-box-body composer-body">
                                    <div class="mb-5">
                                        <label class="pl-5 mb-5"><span id="">ถึง</span> <span class="text-danger">*</span></label>
                                        <select class="form-control" name="email_to[]" id="email_to" style="width: 100%;" multiple="multiple">

                                        </select>

                                    </div>
                                    <div class="mb-5">
                                        <label class="pl-5 mb-5"><span id="">สำเนา</span> </label>
                                        <select class="form-control" name="email_cc[]" id="email_cc" style="width: 100%;" multiple="multiple">
                                        </select>
                                    </div>
                                    <div class="mb-5">
                                        <label class="pl-5 mb-5"><span id="">สำเนาลับ</span> </label>
                                        <select class="form-control" name="email_bcc[]" id="email_bcc" style="width: 100%;" multiple="multiple">
                                        </select>
                                    </div>

                                    <div class="mb-5">
                                        <label class="pl-5 mb-5"><span id="">เรื่อง</span> <span class="text-danger">*</span></label>
                                        <input type="text" class="base-input" id="email_title" name="email_title" value="" required>
                                    </div>

                                    <div class="mb-5">
                                        <label class="pl-5 mb-5"><span id="">ไฟล์แนบ</label>
                                        <div class="flex attach-box">
                                            <div class="flex-none p-5">
                                                <img src="<?php echo base_url('assets/icons/pdf.png'); ?>" style="width:25px">
                                            </div>
                                            <div class="flex-1 text-line-clamp-1 pt-5 pb-5" name="attachname" id="attachname"></div>
                                            <input type="hidden" name="path_file" id="path_file" value="" />
                                            <input type="hidden" name="path" id="path" value="" />
                                            <input type="hidden" name="filename" id="filename" value="" />
                                            <div class="flex-none pt-10 attach-file-size">575KB</div>

                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label class="pl-5 mb-5"><span id="">เนื้อหาอีเมล</span> </label>
                                        <textarea class="base-input" id="email_body" name="email_body" rows="8"></textarea>
                                    </div>

                                    <div class="composer-buttons">
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="button" class="btn btn-default width-full" onclick="Javascript:window.location.href = '<?php echo site_url('/email/index?crmid=' . $crmid . '&userid=' . $userid . '/back'); ?>';">ยกเลิก</button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="btn btn-primary width-full" onclick="$.submitEmail()">ส่งอีเมล</button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!--/.Content-->

    </div>
</div>
<script type="text/javascript">
    $('.overlay').show();
    var crmid = '<?php echo $crmid; ?>'
    var userid = '<?php echo $userid; ?>'
    var email_account = [];
    var email_assignto = [];

    $.post('<?php echo site_url('Email/index'); ?>', {
        crmid,
        userid
    }, function(rs) {
        $('.overlay').hide();
        // console.log(rs)
        email_account = rs.email_account
        email_assignto = rs.email_assignto

        var subject = rs.subject

        var reportData = rs.reportData
        var exportPath = reportData.exportPath
        var fileName = reportData.fileName
        var filePath = reportData.filePath
        var rootDir = reportData.rootDir
        var status = reportData.status


        $('#email_title').val(rs.subject)

        $('#filename').val(fileName)
        $('#path_file').val(filePath)
        $('#path').val(rootDir + exportPath + fileName)
        $('#attachname').append(fileName)
        // console.log(email_account)
        // console.log(email_assignto)

        $("#email_to,#email_cc,#email_bcc").select2({
            dropdownParent: $('#modal-form-email'),
            data: rs.email_allUser
        })

        $("#email_to,#email_cc").select2({
            dropdownParent: $('#modal-form-email')
        }).val(email_account).trigger("change");

        $("#email_cc").select2({
            dropdownParent: $('#modal-form-email')
        }).val(email_assignto).trigger("change");

        $("#email_to,#email_cc,#email_bcc").select2({
            dropdownParent: $('#modal-form-email'),
            tags: true,

            createTag: function(params) {
                // console.log(params)
                var term = $.trim(params.term);

                if (term === '') {
                    return null;
                }
                if (validateEmail(term)) {
                    return {
                        id: term,
                        text: term,
                        newTag: true // add additional parameters
                    };
                }
            }
        });

    }, 'json')

    function validateEmail(email) {
        // console.log(email)
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    $(function() {


        $('#email_to').blur(function(e) {
            var emailTo = $('#email_to').val()
            if (emailTo === '') {
                $('#email_to').addClass('input-danger')
            } else {
                $('#email_to').removeClass('input-danger')
            }
        })

        $('#email_title').blur(function(e) {
            var emailTitle = $('#email_title').val()
            if (emailTitle === '') {
                $('#email_title').addClass('input-danger')
            } else {
                $('#email_title').removeClass('input-danger')
            }
        })

        $.submitEmail = function() {
            var emailTo = $('#email_to').val();
            var emailCC = $('#email_cc').val();
            var emailBCC = $('#email_bcc').val();
            var emailTitle = $('#email_title').val();
            var pathFile = $('#path_file').val();
            var path = $('#path').val();
            var fielName = $('#filename').val();
            var emailBody = $('#email_body').val();

            if (emailTo.length == 0) {
                $('#email_to').siblings(".select2-container--default").css({
                    'border': '1px solid red',
                    'border-radius': '10px'
                })
                return false;
            } else {
                $('#email_to').siblings(".select2-container--default").css('border', '0px solid');
            }

            if (emailTitle === '') {
                $('#email_title').addClass('input-danger')
                return false;
            } else {
                $('#email_title').removeClass('input-danger')
            }

            $('.overlay').show();
            $.post('<?php echo site_url('Email/send'); ?>', {
                crmid,
                userid,
                emailTo,
                emailCC,
                emailBCC,
                emailTitle,
                pathFile,
                path,
                fielName,
                emailBody
            }, function(rs) {
                console.log(rs['Status'])

                if (rs['Status'] === "true") {
                    // $('#loader').fadeOut();
                    Swal.fire('', rs['Message'], 'success')
                    $('#modal-form-email').modal('hide');
                } else {
                    // $('#loader').fadeOut();
                    Swal.fire('', rs['Message'], 'error')

                }
                $('.overlay').hide();
            }, 'json')

        }

    })
</script>