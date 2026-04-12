<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
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
<!-- Page Content -->
<div class="page-content">
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
                    <!-- <div data-bs-toggle="collapse" href="#box0" role="button" aria-expanded="true" class="">
                        <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                    </div> -->
                </div>
            </div>
            <div class="collapse show">
                <div class="card-box-body composer-body">
                    <div class="mb-5">
                        <label class="pl-5 mb-5"><span id="">ถึง</span> <span class="text-danger">*</span></label>
                        <select class="form-control" name="email_to[]" id="email_to" multiple="multiple">
                            <?
                            for ($k = 0; $k < count($email_allUser); $k++) {
                            ?>
                                <option value="<?= $email_allUser[$k] ?>" title="<?= $email_allUser[$k] ?>"><?= $email_allUser[$k] ?></option>
                            <?
                            }
                            ?>
                        </select>

                    </div>

                    <div class="mb-5">
                        <label class="pl-5 mb-5"><span id="">สำเนา</span> </label>
                        <select class="form-control" name="email_cc[]" id="email_cc" multiple="multiple">
                            <?
                            for ($k = 0; $k < count($email_allUser); $k++) {
                            ?>
                                <option value="<?= $email_allUser[$k] ?>" title="<?= $email_allUser[$k] ?>"><?= $email_allUser[$k] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="pl-5 mb-5"><span id="">สำเนาลับ</span> </label>
                        <select class="form-control" name="email_bcc[]" id="email_bcc" multiple="multiple">
                            <?
                            for ($k = 0; $k < count($email_allUser); $k++) {
                            ?>
                                <option value="<?= $email_allUser[$k] ?>" title="<?= $email_allUser[$k] ?>"><?= $email_allUser[$k] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="pl-5 mb-5"><span id="">เรื่อง</span> <span class="text-danger">*</span></label>
                        <input type="text" class="base-input" id="email_title" name="email_title" value="<?php echo $subject ?>" required>
                    </div>

                    <div class="mb-5">
                        <label class="pl-5 mb-5"><span id="">ไฟล์แนบ</label>
                        <div class="flex attach-box">
                            <?php if ($reportData['status']) { ?>
                                <div class="flex-none p-5">
                                    <img src="<?php echo base_url('assets_email/icons/pdf.png'); ?>" style="width:25px">
                                </div>
                                <div class="flex-1 text-line-clamp-1 pt-5 pb-5"><?php echo $reportData['fileName']; ?></div>
                                <input type="hidden" name="path_file" id="path_file" value="<?php echo $reportData['filePath']; ?>" />
                                <input type="hidden" name="path" id="path" value="<?php echo $reportData['rootDir'] . $reportData['exportPath'] . $reportData['fileName']; ?>" />
                                <input type="hidden" name="filename" id="filename" value="<?php echo $reportData['fileName']; ?>" />
                                <div class="flex-none pt-10 attach-file-size">575KB</div>
                            <?php } else { ?>
                                <div style="height: 35px;"></div>
                            <?php } ?>
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

<script type="text/javascript">
    var crmID = '<?php echo $crmid; ?>';
    var userID = '<?php echo $userid; ?>';
    var email_account = <?php echo json_encode($email_account); ?>;
    var email_assignto = <?php echo json_encode($email_assignto); ?>;

    $("#email_to,#email_cc,#email_bcc").select2();
    $("#email_to,#email_cc").select2({}).val(email_account).trigger("change");
    $("#email_cc").select2({}).val(email_assignto).trigger("change");

    $("#email_to,#email_cc,#email_bcc").select2({
        tags: true,
        // tokenSeparators: [';', ' '],

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
                crmID,
                userID,
                emailTo,
                emailCC,
                emailBCC,
                emailTitle,
                pathFile,
                path,
                fielName,
                emailBody
            }, function(rs) {
                // console.log(rs)

                if (rs['status'] == "1") {
                    // $('#loader').fadeOut();
                    Swal.fire('', rs['Message'], 'success')

                    setTimeout(function() {
                        // location.reload();
                        window.location.href = '<?php echo site_url('/email/index?crmid=' . $crmid . '&userid=' . $userid . '/back'); ?>'
                    }, 1000);

                } else {
                    // $('#loader').fadeOut();
                    Swal.fire('', rs['Message'], 'error')

                }
                $('.overlay').hide();
            }, 'json')

        }

    })
</script>