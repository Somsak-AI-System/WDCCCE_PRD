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
        <!-- <?php
                echo '<pre>';
                print_r($action);
                echo '</pre>'
                ?> -->
            <div class="top-nav-back-icon" onclick="location.href='<?php echo site_url('Projects/view/' . $crmid . '?userid=' . $userid); ?>'"><i class="ph-caret-left"></i></div>
        
        <span>รายงานโครงการ</span>
        <div class="top-nav-action flex">
            <div class="top-nav-action-icon flex-none">
                <div>
                    <i class="ph-dots-three-circle" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu dropdown-menu-end top-nav-dropdown">
                        <li>
                            <button class="dropdown-item" type="button" onclick="$.showFormEmail()">
                                <i class="ph-envelope v-align-middle"></i> ส่งอีเมล
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" onclick="$.showFormShare()">
                                <i class="ph-share v-align-middle"></i> แชร์
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Top Navbar -->

<!-- Page Content -->
<div class="page-content mt-48">
    <div class="width-full p-10 bg-white" style="position:fixed; left:0px; top:48px;">
        <iframe src="<?php echo $url; ?>" style="position:fixed; top:65px; left:10px; bottom:20px; right:0px; width:95%; height:100%; margin:0; " frameborder="0">
        </iframe>
    </div>
</div>
<!-- End Page Content -->
<?php $this->load->view('form-email'); ?>
<?php $this->load->view('form-share'); ?>

<script>
    
    $(function() {

        $.showFormEmail = function() {
            $('#modal-form-email').modal('show');
        }

        $.showFormShare = function() {
            $('#modal-form-share').modal('show');
        }

    })
</script>