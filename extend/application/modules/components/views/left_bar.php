<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- <div class="d-flex no-block nav-text-box align-items-center">
        <span><img src="../assets/images/logo-icon.png" alt="elegant admin template"></span>
        <a class="nav-lock waves-effect waves-dark ml-auto hidden-md-down" href="javascript:void(0)"><i class="mdi mdi-toggle-switch"></i></a>
        <a class="nav-toggler waves-effect waves-dark ml-auto hidden-sm-up" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
    </div> -->

    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="iconleftbar disabled"> 
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <!-- <i class="fa fa-phone"></i> -->
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/phoneb.png'); ?>" 
                                onmouseover="this.src='<?php echo site_assets_url('images/icons/phonew.png'); ?>'"
                                onmouseout="this.src='<?php echo site_assets_url('images/icons/phoneb.png'); ?>'"
                                border="0" alt="" width="15" height="15"/>
                        </i>
                    </a>
                </li>
                <li class="iconleftbar"> 
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <!-- <i class="fa fa-comments"></i> -->
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/smsb.png'); ?>" 
                                onmouseover="this.src='<?php echo site_assets_url('images/icons/smsw.png'); ?>'"
                                onmouseout="this.src='<?php echo site_assets_url('images/icons/smsb.png'); ?>'"
                                border="0" alt="" width="17" height="14"/>
                        </i>
                    </a>
                </li>
                <li class="iconleftbar disabled"> 
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <!-- <i class="fa fa-envelope"></i> -->
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/sendemailb.png'); ?>" 
                                onmouseover="this.src='<?php echo site_assets_url('images/icons/sendemailw.png'); ?>'"
                                onmouseout="this.src='<?php echo site_assets_url('images/icons/sendemailb.png'); ?>'"
                                border="0" alt="" width="17" height="14"/>
                        </i>
                    </a>
                </li>
                <li class="iconleftbar disabled"> 
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <!-- <i class="fa fa-bell"></i> -->
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/notificationnavib.png'); ?>" 
                                onmouseover="this.src='<?php echo site_assets_url('images/icons/notificationnaviw.png'); ?>'"
                                onmouseout="this.src='<?php echo site_assets_url('images/icons/notificationnavib.png'); ?>'"
                                border="0" alt="" width="15" height="17"/>
                        </i>
                    </a>
                </li>
                <li class="iconleftbar disabled"> 
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <!-- <i class="fa fa-calendar"></i> -->
                        <i>
                            <img src="<?php echo site_assets_url('images/icons/holidayb.png'); ?>" 
                                onmouseover="this.src='<?php echo site_assets_url('images/icons/holidayw.png'); ?>'"
                                onmouseout="this.src='<?php echo site_assets_url('images/icons/holidayb.png'); ?>'"
                                border="0" alt="" width="15" height="15"/>
                        </i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->

<style>

    .left-sidebar {
        top: 65px;
        z-index: 0;
    }

    /*.left-sidebar:hover {
        left: 0px;
    }*/

    .skin-default-dark .left-sidebar {
        background: #ffffff;
        margin-top: -9px;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
    }

    .skin-default-dark .left-sidebar .sidebar-nav ul li a i {
        color: #000000;
    }

    .iconleftbar {
        background: #fff;
        color: #000;
    }

    .iconleftbar:hover {
        background: #FFA500;
        border-radius: 5px;
    }

    .disabled {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
        color: black;
        opacity: 0.5;
    }



</style>