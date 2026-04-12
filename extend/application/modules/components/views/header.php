<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo site_url() ?>">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="<?php echo site_assets_url('images/Logo_MOAIOC.png'); ?>" height="60" alt="homepage" class="dark-logo"/>
                    <!-- Light Logo icon -->
                    <!-- <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" /> -->
                </b>
                <!--End Logo icon -->
                <!-- Logo text --><span>
                   <!-- dark Logo text -->
                   <!-- <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo" /> -->
                   <!-- Light Logo text -->    
                   <!-- <img src="../assets/images/logo-light-text.png" class="light-logo" alt="homepage" /></span>  -->
               </a>
           </div>
           <!-- ============================================================== -->
           <!-- End Logo -->
           <!-- ============================================================== -->
           <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto">
                <!-- This is  -->
                <li class="nav-item hidden-sm-up"> <a class="nav-link nav-toggler waves-effect waves-light" href="javascript:void(0)"><i class="ti-menu"></i></a></li>
                <li class="nav-item boxagent">
                    <a class="nav-link dropdown-toggle" href="<?php echo site_url('omnichannel') ?>">
                        <i><img src="<?php echo site_assets_url('images/icons/icon_omnichannel_black.png'); ?>" width="20" height="20"/></i>
                        &nbsp;
                        <span style="color: #2B2B2B; font-family: PromptMedium;">OMNI-CHANNEL</span>
                    </a>
                </li>
                <li class="nav-item boxagent">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)">
                        <i><img src="<?php echo site_assets_url('images/icons/agent.png'); ?>" width="20" height="20"/></i>
                        &nbsp;
                        <span style="color: #2B2B2B; font-family: PromptMedium;">AGENT</span>
                    </a>
                </li>
                <li class="nav-item boxagent">
                    <a class="nav-link dropdown-toggle" href="<?php echo site_url('omnidashboard') ?>">
                        <i><img src="<?php echo site_assets_url('images/icons/icon_dashboard_black.png'); ?>" width="20" height="20"/></i>
                        &nbsp;
                        <span style="color: #2B2B2B; font-family: PromptMedium;">DASHBOARD</span>
                    </a>
                </li>
                <li class="nav-item boxagent dropdown">
                    <a class="nav-link dropdown-toggle" href="<?php echo site_url('omnireport') ?>">
                         <i><img src="<?php echo site_assets_url('images/icons/icon_report_black.png'); ?>" width="20" height="20"/></i>
                        &nbsp;
                        <span style="color: #2B2B2B; font-family: PromptMedium;">REPORT</span>
                        <i class="ti-angle-down" style="color: #000;"></i>
                    </a>
                    <div class="dropdown-content" style="position: fixed;">
                        <a href="<?php echo site_url('omnireport') ?>" style="color: #2B2B2B; font-family: PromptMedium;">Report Response History</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <!-- mega menu -->
                <!-- ============================================================== -->
                <li class="nav-item"> 
                    <div class="iconfontsize">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span style="color:#2B2B2B;">A+</span>
                        </a>
                    </div>
                </li>
                <li class="nav-item"> 
                    <div class="iconfontsize">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span style="color: #2B2B2B;">A-</span>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown mega-dropdown"> 
                    <div class="boxChangelanguage">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <!-- <span style="color: #2B2B2B;">TH</span>
                            &nbsp; -->
                            <!-- <?php if($this->session->userdata('lang')=='thailand'){ ?>
                                <span style="color: #2B2B2B;">TH</span>&nbsp;
                            <?php }else if($this->session->userdata('lang')=='english'){ ?>
                                <span style="color: #2B2B2B;">EN</span>&nbsp;
                            <?php } ?> -->
                            <!-- <span style="color: #2B2B2B;">EN</span>&nbsp; -->
                            <?php if($this->session->userdata('lang')=='thailand'){ ?>
                                <span style="color: #2B2B2B;">TH</span>&nbsp;
                            <?php }else { ?>
                                <span style="color: #2B2B2B;">EN</span>&nbsp;
                            <?php } ?>
                            <i class="ti-angle-down" style="color: #000;"></i>
                        </a>
                    </div>
                    <div class="dropdown-content">
                      <a href="javascript:;" onclick="$.changeLang('thailand')">
                        TH
                    </a>
                    <a href="javascript:;" onclick="$.changeLang('english')">
                       EN
                    </a>
                    </div>
                </li>
                <li class="nav-item disabled"> 
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-left: 15px; padding-right: 15px;">
                        <i><img src="<?php echo site_assets_url('images/icons/notification.png'); ?>" width="20" height="20"/></i>
                        <div class="notify"> 
                            <!-- <span class="badge">3</span> -->
                        </div>
                    </a>
                </li>
               <li class="nav-item">
                    <div class="column" style="margin-top: 13px; width: 200px;">
                        <div style="display: inline-flex;">
                            <!-- <img src="<?php echo site_assets_url('images/tmp_user.png'); ?>" alt="user" class="img-circle" width="30" height="30">&nbsp;&nbsp; -->
                            <?php if($_SESSION['imageuser'] != ''){ ?>
                              <img src="<?php echo $_SESSION['imageuser']; ?>" alt="user" class="img-circle" width="30" height="30">
                            <?php }else{ ?>
                            <img src="<?php echo site_assets_url('images/tmp_user.png'); ?>" alt="user" class="img-circle" width="30" height="30">
                            <?php } ?>&nbsp;&nbsp;
                            <div class="name">
                              <span title="<? echo $_SESSION['fullname']; ?>"><? echo mb_substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $_SESSION['fullname']), 0, 18,"utf-8") . '...';?></span>
                              <br>
                              <span style="color: #9c9c9c;font-size: 12px;">เข้าใช้งานล่าสุด <? echo date('d M Y');?></span>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item right-side-toggle"> 
                    <a class="nav-link  waves-effect waves-light" href="home/logout"><!-- href="javascript:void(0)" -->
                        <img src="<?php  echo site_assets_url('images/icons/logout.png') ?>" class="logoutimage" title="<?php echo genLabel('LBL_LOGOUT') ?>">
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>


<div id="modalOderinquiry" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2b2b2b;">
                <b>Order Inquiry</b>
            </span>
            <span class="close" id="closeOrderinquiry"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
        </div>
        <div class="modal-body" style="margin-top: 10px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <form id="form_order" action="#" method="POST">
                            <div class="row">
                                <div class="col-2" style="margin: auto; text-align: center; font-family: PromptMedium; font-size: 11px; color: #2b2b2b;">
                                    <b>ค้นหาใบจอง</b>
                                </div>
                                <div class="col-10" style="border: 1px solid #ededed; font-family: PromptMedium; font-size: 11px; color: #2b2b2b;">
                                    <div class="row m-t-10" style="padding-left: 15px;">
                                        <div class="col-sm">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">Contact ID :</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" type="text" name="contactid" id="contactid_orderinquiry" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label">Telephone :</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" type="text" name="telephone" id="telephone_orderinquiry" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label">Line :</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" type="text" name="line_id" id="line_id" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-right" style="margin-right: -10px; margin-top: 10px;">
                                        <div class="col-sm">
                                            <button type="button" class="btn btn-success d-none d-lg-block btncallin fillterorder" style="margin-top: -55px;">
                                                <i>
                                                    <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" />
                                                </i>
                                                ค้นหา
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 15px; margin-top: -10px;">
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label">Contact Name :</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" type="text" name="conract_name" id="conract_name" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label">Project/Address :</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" type="text" name="project_address" id="project_address" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label">Delivery Location :</label>
                                                <div class="col-sm-7">
                                                    <input class="form-control" type="text" name="delivery_location" id="delivery_location"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 15px; margin-top: -20px;">
                                        <div class="col-sm-12" style="text-align: center;">
                                            <div class="input-daterange input-group" id="date-range" style="padding-bottom: 10px; margin-left: 290px;">
                                                <input type="text" class="form-control col-sm-2" id="date_from" name="date_from" data-date-format="dd/mm/yyyy" value="<?php echo date('d/m/Y'); ?>" />
                                                &nbsp;&nbsp;&nbsp;
                                                <div class="input-group-append">
                                                    <span style="margin: auto; color: #2b2b2b; font-size: 11px;"> - </span>
                                                </div>
                                                &nbsp;&nbsp;&nbsp;
                                                <input type="text" class="form-control col-sm-2" id="date_to" name="date_to" value="<?php echo date('d/m/Y'); ?>" />
                                                &nbsp;&nbsp;&nbsp;
                                                <div class="input-group-append">
                                                    <img src="<?php echo site_assets_url('images/icons/Calendar.png'); ?>" width="15" height="15" style="margin: auto;" />
                                                    &nbsp;
                                                    <img src="<?php echo site_assets_url('images/icons/slidedownb.png'); ?>" width="7" height="5" style="margin: auto;" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="orderiq">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="modalPricelist" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2b2b2b;">
                <b>Pricelist</b>
            </span>
            <span class="close" id="closePricelist"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
        </div>
        <div class="modal-body" style="margin-top: 10px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 p-3" style="box-shadow: 1px 0px 10px #E5E5E5;">
                            <div class="row m-t-10">
                                <div class="col-sm-1 m-t-10 text-right">
                                    <label style="font-family: PromptMedium; font-size: 11px;">
                                        PRICE LIST
                                    </label>
                                </div>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text">
                                </div>
                                <div class="col-sm-1">
                                    <a href="#searchpricelist" data-toggle="tab">
                                        <button type="button" class="btn d-none d-lg-block m-l-15 btnsearch"><i class="fa fa-search"></i> Search </button>
                                    </a>
                                </div>
                            </div>
                            <div class="row m-t-10" style="border-radius: 10px;">
                                <div class="col-sm-2" style="background: #EDEDED; border-radius: 10px 0px 0px 10px;">
                                    <div class="center">
                                        <a data-toggle="tab" href="#menu1">
                                            <button type="button" class="btn d-none d-lg-block btnsearch" id="buttonimport"><i class="fa fa-upload"></i> Start Import </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-10" style="border-radius: 10px; border: 1px solid #F9F9F9;">
                                    <form id="msform">
                                        <ul id="progressbar">
                                              <li id="selectfile">Select file</li>
                                              <li>Validate & Error</li>
                                              <li>Import Approval</li>
                                              <li>Finished</li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="tab-content tabcontent-border m-t-10">
                                <div id="menu1" class="tab-pane fade">
                                    <div class="card" style="box-shadow: 1px 0px 10px #E5E5E5;">
                                        <div class="card-body">
                                            <div id="msform">
                                                <fieldset>
                                                    <div class="row">
                                                            <div class="col-sm-1">
                                                                <button type="button" class="btn d-none d-lg-block m-l-15 m-t- 15 btnbrowse"><i class="fa fa-search"></i> Browse </button>
                                                            </div>
                                                            <div class="col-sm-10">
                                                                <input class="form-control" type="text">
                                                            </div>
                                                        </div>
                                                    <button type="button" name="next" class="btn d-none d-lg-block m-l-15 btnsearch next" style="float: right; width: 100px; margin-right: 15px; margin-top: -37px;"> Import </button>
                                                </fieldset>
                                                <fieldset>
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="card-title text-left" style="color: #000000; ">VALIDATE MESSAGE & ERROR</h4>
                                                                <textarea class="form-control" rows="10" placeholder="This file verified" style="background: #EDEDED;"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" name="next" class="btn d-none d-lg-block m-l-15 btnimportapp next" style="float: right;"> IMPORT APPROVE </button>
                                                </fieldset>
                                                <fieldset>
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h4 class="card-title text-left" style="color: #000000;">IMPORT LOG</h4>
                                                                <div class="col-12" style="height: 250px; background: #EDEDED;">
                                                                    <p class="m-t-10" style="float: left; color: #00E196;">Import finish</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" name="next" class="btn d-none d-lg-block m-l-15 btnimportapp next" style="float: right;">Close</button>
                                                </fieldset>
                                                <fieldset>
                                                   
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="searchpricelist" class="tab-pane fade">
                                    <div class="card" style="box-shadow: 1px 0px 10px #E5E5E5;">
                                        <div class="card-body">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="example" class="table table-bordered">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th>PlantID</th>
                                                                <th>ชื่อแพล้นท์</th>
                                                                <th>ที่ตั้งแพล้นท์</th>
                                                                <th>lat.</th>
                                                                <th>long.</th>
                                                                <th>แบรนด์</th>
                                                                <th>ซีเมนต์</th>
                                                                <th>เบอร์เซลล์</th>
                                                                <th>เบอร์แพลนท์</th>
                                                                <th>ไลน์</th>
                                                                <th>บริษัท</th>
                                                                <th>จังหวัด</th>
                                                                <th>เพิ่มเติม</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>500000401</td>
                                                                <td>QMIX โรงงานสารภี</td>
                                                                <td>ตำบล</td>
                                                                <td>18.00</td>
                                                                <td>99.00</td>
                                                                <td></td>
                                                                <td>SCG</td>
                                                                <td>0898876676</td>
                                                                <td>0812237765</td>
                                                                <td>Q</td>
                                                                <td>บ.</td>
                                                                <td>เชียงใหม่</td>
                                                                <td>รายละเอียด</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->


<div id="modalAccountinquiry" class="modal">
<!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2B2B2B;"><b>Account Inquiry</b></span>
            <span class="close" id="closeaccoinquiry"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span>
            <br>
        </div>
        <div class="modal-body" style="margin-top: 10px;">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-11 text-center" style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
                    แสดงข้อมูลลูกค้าครั้งละ 20 รายการ (จากทั้งหมด <span id="totaltext"></span> รายการ)
                    </div>
                    <div class="col-sm text-right">
                        <button type="button" class="btn btnadd">
                            <i>
                                <img src="<?php echo site_assets_url('images/icons/addcontento.png'); ?>" width="15" height="15" />
                            </i> <b>เพิ่มข้อมูล</b></button>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div id="grid_accountinquiry"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">

    @font-face {
      font-family: PromptMedium;
      src: url(assets/fonts/Prompt-Medium.ttf);
    }

    .boxagent {
        background: #fff;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }

    .dropdown-content a {
      float: none;
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .iconfontsize {
        background: #EDEDED;
        border-radius: 20px;
        color: #000;
        margin-top: 13px;
        margin-right: 10px;
        font-family: PromptMedium;
    }

    .boxChangelanguage {
        margin-top: 13px;
        background: #EDEDED;
        border-radius: 10px;
        font-family: PromptMedium;
    }

    .notify .point {
        background-color: #0D9E52;
    }

    .logoutimage {
        float: right;
        width: 25px;
        height: 25px;
        margin-top: 13px;
    }

    .name {
        font-family: PromptMedium;
    }

    .topbar .top-navbar .navbar-nav>.nav-item>.nav-link {
        font-family: PromptMedium;
        font-size: 12px;
        font-weight: 500;
    }

    .topbar .top-navbar .navbar-header {
        padding-left: 30px;
        padding-right: 30px;
        min-width: 150px;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        border-right: none;
    }

    .topbar .top-navbar .navbar-nav>.nav-item>.nav-link {
        padding-left: 25px;
        padding-right: 25px;
    }

    .dropdown-content {
        font-family: PromptMedium;
    }

    .notify .point {
        background-color: #FF4560;
    }

    .notify .badge {
      position: absolute;
      top: -17px;
      right: 2px;
      padding: 0px 4px;
      border-radius: 50%;
      background-color: #FF4560;
      color: white;
    }

    .skin-default-dark .topbar {
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
    }

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        overflow-y: initial !important;
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .modal-body {
        /*overflow-y: auto;
        max-height: calc(100vh - 200px);*/
        max-height: 100%;
    }

    /* The Close Button */
    .close {
        color: #2b2b2b;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #2b2b2b;
        text-decoration: none;
        cursor: pointer;
    }

    /* The Close Button */
    .close2 {
        color: #2b2b2b;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close2:hover,
    .close2:focus {
        color: #2b2b2b;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-header {
        padding: 2px 16px;
        /*background-color: #5cb85c;*/
        color: #000000;
    }

    .btn:hover {
        color: #E97126;
    }

    .btnadd {
        font-size: 11px;
        box-shadow: 1px 0px 10px #EDEDED;
        font-family: PromptMedium;
        color: #E97126;
    }

    .btnsearch {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
        box-shadow: 1px 0px 10px #E5E5E5;
        font-size: 11px;
        font-family: PromptMedium;
    }

    .btnsearch:hover {
        background: #fff;
        color: #e8874b;
        box-shadow: 1px 0px 10px #FBEDE7;
    }

    .btnimportapp {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
        box-shadow: 1px 0px 10px #E5E5E5;
    }

    .btnimportapp:hover {
        background: #fff;
        color: #e8874b;
        box-shadow: 1px 0px 10px #FBEDE7;
    }

    .center {
      margin: 0;
      position: absolute;
      top: 50%;
      left: 50%;
      -ms-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
    }

    #msform {
        text-align: center;
        position: relative;
        margin-top: 20px
    }

    #msform fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    .form-card {
        text-align: left
    }

    #msform fieldset:not(:first-of-type) {
        display: none
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey;
        counter-reset: step;
    }

    #progressbar .active {
        /*color: #00E196;*/
    }

    #progressbar li {
      list-style-type: none;
      width: 25%;
      float: left;
      font-size: 12px;
      position: relative;
      text-align: center;
      text-transform: uppercase;
      color: #000000;
    }

    /*#progressbar #account:before {
        font-family: FontAwesome;
        content: "\f13e"
    }

    #progressbar #personal:before {
        font-family: FontAwesome;
        content: "\f007"
    }

    #progressbar #payment:before {
        font-family: FontAwesome;
        content: "\f030"
    }

    #progressbar #confirm:before {
        font-family: FontAwesome;
        content: "\f00c"
    }*/

    #progressbar li:before {
      width: 50px;
      height: 50px;
      content: counter(step);
      counter-increment: step;
      line-height: 30px;
      border: 2px solid #F1F1F1;
      display: block;
      text-align: center;
      margin: 0 auto 10px auto;
      border-radius: 50%;
      background-color: #EDEDED;
      color: #EDEDED;
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: -50%;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #00E196;
        color: #00E196;
    }

    #progressbar li:first-child:after {
      content: none;
    }

    /*#msform input,
    #msform textarea {
        padding: 8px 15px 8px 15px;
        border: 1px solid #ccc;
        border-radius: 0px;
        margin-bottom: 25px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        font-family: montserrat;
        color: #2C3E50;
        background-color: #ECEFF1;
        font-size: 16px;
        letter-spacing: 1px
    }

    #msform input:focus,
    #msform textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: 1px solid #673AB7;
        outline-width: 0
    }*/

    #msform .action-button {
        width: 100px;
        background: #673AB7;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 0px 10px 5px;
        float: right
    }

    #msform .action-button:hover,
    #msform .action-button:focus {
        background-color: #311B92
    }

    #msform .action-button-previous {
        width: 100px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px 10px 0px;
        float: right
    }

    #msform .action-button-previous:hover,
    #msform .action-button-previous:focus {
        background-color: #000000
    }

    .card {
        z-index: 0;
        border: none;
        position: relative
    }

    .fs-title {
        font-size: 25px;
        color: #673AB7;
        margin-bottom: 15px;
        font-weight: normal;
        text-align: left
    }

    .purple-text {
        color: #673AB7;
        font-weight: normal
    }

    .steps {
        font-size: 25px;
        color: gray;
        margin-bottom: 10px;
        font-weight: normal;
        text-align: right
    }

    .fieldlabels {
        color: gray;
        text-align: left
    }

    .btnbrowse {
        background: #EDEDED;
    }

    /*.btnimportapp {
        background: #fff;
        color: #e8874b;
        width: 150px;
        border-radius: 10px;
        box-shadow: 1px 0px 10px #FBEDE7;
    }*/

    .col-form-label {
        font-size: 11px;
    }

    .topbar .top-navbar .navbar-header {
        line-height: 55px;
    }

    .topbar .top-navbar .navbar-nav>.nav-item>.nav-link {
        line-height: 40px;
    }

</style>

<!-- <script>
    $(function () {
        $.changeLang = function ($lang) {
            $.post(site_url('Api/changeLang'), {lang:$lang}, function(rs){
                location.reload();
            })
        }
    })
</script> -->



<script>

    /*$.logout = function () { 

        var url = "<?php //echo site_url('home/logout'); ?>";
        $.ajax(url, {
            type: "POST",
            data: "",
            success: function (data) {
               console.log(data);
               window.location.hostname
            },
            error: function (data) {
             //console.log("f");
            },
        });

    }*/

    $.orderinquiry = function () { 

        var modalOderinquiry = document.getElementById("modalOderinquiry");
        var closeOrderinquiry = document.getElementById("closeOrderinquiry");

        modalOderinquiry.style.display = "block";

        closeOrderinquiry.onclick = function() {
            modalOderinquiry.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modalOderinquiry) {
                modalOderinquiry.style.display = "none";
            }
        };

        // $( "#date_from" ).datepicker();

        var date_from = $('#date_from').val();
        console.log(date_from);

        var date_to = $('#date_to').val();
        console.log(date_to);



        var url = "<?php echo site_url('home/getorder'); ?>";

        $.ajax(url, {
            type: "POST",
            data: "" + "&date_from=" + date_from + "&date_to=" + date_to,
            success: function (html) {
                // console.log(html);
                $('#modalOderinquiry .modal-body .orderiq').html(html);
                /*var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    console.log("S");
                } else {
                    console.log("E");
                }*/

            },
            error: function (data) {
                console.log("f");
            },
        });

        $(document).on("click", ".fillterorder", function () { 
            console.log("click");

            var formorder = $("#form_order").serialize();
            var url = "<?php echo site_url('home/getorder'); ?>"

            $.ajax(url, {
                type: "POST",
                data: formorder,
                success: function(html) {
                    // console.log(html);
                    $('#modalOderinquiry .modal-body .orderiq').html(html);

                },
                error: function(data) {
                    console.log("f");
                },
            });

        });


    }

    $.pricelist = function() {
        var modalPricelist = document.getElementById("modalPricelist");
        var closePricelist = document.getElementById("closePricelist");

        modalPricelist.style.display = "block";

        closePricelist.onclick = function() {
            modalPricelist.style.display = "none";
        }
    }

    $.accountsinquiry = function() {

        var modalAccountinquiry = document.getElementById("modalAccountinquiry");
        var closeaccoinquiry = document.getElementById("closeaccoinquiry");

        modalAccountinquiry.style.display = "block";

        closeaccoinquiry.onclick = function() {
            modalAccountinquiry.style.display = "none";
        }

         window.onclick = function (event) {
            if (event.target == modalAccountinquiry) {
                modalAccountinquiry.style.display = "none";
            }
        };

        var datasource = {
            pageSize: 20,
            filter: {
                logic: "and",
                filters: [],
            },
            schema: {
                model: {
                    id: "",
                },
                data: "data",
                total: "pagesize",
            },
            serverPaging: false,
            serverFiltering: false,
            serverSorting: false,
        };

        var columns = [
            // { template: "<input type='checkbox' class='checkbox' />" },
            { selectable: true, width: "50px" },
            {
                field: "account_no",
                title: "รหัสลูกค้า",
                width: 100,
            },
            {
                field: "first_name",
                title: "ชื่อลูกค้า",
                width: 100,
            },
            {
                field: "accountstatus",
                title: "สถานะลูกค้า",
                width: 100,
            },
            {
                field: "accounttype",
                title: "ประเภทลูกค้า",
                width: 100,
            },
            {
                field: "phone",
                title: "เบอร์โทร",
                width: 100,
            },
            {
                field: "line_id",
                title: "ไลน์",
                width: 100,
            },
            {
                field: "register_date",
                title: "วันที่เริ่มเป็นลูกค้า",
                width: 100,
            },
            {
                field: "truck_size",
                title: "Action",
                width: 100,
            },  
        ];

        $("#grid_accountinquiry").genKendoGrid(datasource, columns);

        var url = "<?php echo site_url('home/getaccount'); ?>";

        $.ajax(url, {
            type: "POST",
            data: "",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result);
                if (result["Type"] == "S") {
                    console.log("success");
                    $("#grid_accountinquiry").data("kendoGrid").dataSource.data([]);
                    var grid_accountinquiry = $("#grid_accountinquiry").data("kendoGrid");
                    $.each(result.data, function (key, value) {
                        grid_accountinquiry.dataSource.add({
                            account_no: value.account_no,
                            first_name: value.first_name,
                            accountstatus: value.accountstatus,
                            accounttype: value.accounttype,
                            phone: value.phone,
                            line_id: value.line_id,
                            register_date: value.register_date,
                            
                        });
                    });

                    var grid = $("#grid_accountinquiry").data("kendoGrid");
                    var dataSource = grid.dataSource;
                    //records on current view / page  
                    // var recordsOnCurrentView = dataSource.view().length;
                    //total records
                    var totalRecords = result.total;

                    document.getElementById("totaltext").textContent = totalRecords;
                     
                    // console.log(dataSource);
                    // console.log("totalRecords : " + totalRecords);
                    // console.log("recordsOnCurrentView : " + recordsOnCurrentView);

                } else {
                    console.log("f");
                    $("#grid_accountinquiry").data("kendoGrid").dataSource.data([]);

                    // var totalRecords = result.total;

                    document.getElementById("totaltext").textContent = 0;
                }
            },
            error: function (data) {
                console.log("f");
            },
        });

    }

    


    // var modalOderinquiry = document.getElementById("modalOderinquiry");

    // var orderinquiry = document.getElementById("orderinquiry");

    // var closeOrderinquiry = document.getElementById("closeOrderinquiry");

    // orderinquiry.onclick = function() {
    //     modalOderinquiry.style.display = "block";
    // }

    // // When the user clicks on <span> (x), close the modal
    // closeOrderinquiry.onclick = function() {
    //   modalOderinquiry.style.display = "none";
    // }

    // // When the user clicks anywhere outside of the modal, close it
    // window.onclick = function(event) {
    //   if (event.target == modalOderinquiry) {
    //     modalOderinquiry.style.display = "none";
    //   }
    // }

</script>

<!-- <script src="<?php echo site_assets_url('css/node_modules/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script> -->

<!-- <script>

    $("#buttonimport").click(function() {
      $("#selectfile").addClass('active');
    });

    $(document).ready(function(){

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;
        var current = 0;
        var steps = $("fieldset").length;

        setProgressBar(current);

        $(".next").click(function(){

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
        step: function(now) {
        // for making fielset appear animation
        opacity = 1 - now;

        current_fs.css({
        'display': 'none',
        'position': 'relative'
        });
        next_fs.css({'opacity': opacity});
        },
        duration: 500
        });
        setProgressBar(++current);
        });

        function setProgressBar(curStep){
        var percent = parseFloat(100 / steps) * curStep;
        
        }

    });

$(document).ready(function() {

    var table = $('#example').DataTable({
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            "order": [[2, 'asc']],
            // "displayLength": 25,
            "pageLength": 10,
        });
    });

</script> -->


