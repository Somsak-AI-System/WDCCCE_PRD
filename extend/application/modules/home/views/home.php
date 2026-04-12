<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid" style="margin-top: -20px;">
        <div class="card-group row1" style="margin-bottom: 5px;">
            <!-- card -->
            <div class="card o-income" style="border-radius: 0px 0px 5px 5px;">
                <div class="card-body">
                    <form id="form_save_contacts" action="#" method="POST">
                        <div class="page-titles d-flex align-items-center no-block">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="" style="margin-top: 3px; cursor: default; pointer-events: none; text-decoration: none;">
                                        <span class="hidden-xs-down"><?php echo genLabel('LBL_CONTACT_INFORNMATION'); ?></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="ml-auto" style="margin-top: 2px;">
                                <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                                    <!-- <li class="disabled">
                                        <a href="">
                                            <i><img src="<?php echo site_assets_url('images/icons/phoneb.png'); ?>" width="15" height="15" /></i>
                                            <?php echo genLabel('LBL_CALL_IN') ?>
                                        </a>
                                    </li> -->
                                    <li style="background: #018ffb;">
                                        <a href="javascript:void(0)" style="color: #ffffff;" onclick="$.saveContacts();">
                                            <i><img src="<?php echo site_assets_url('images/icons/savew.png'); ?>" width="15" height="15" /></i>
                                            <?php echo genLabel('LBL_SAVE') ?>
                                        </a>
                                    </li>
                                    <!-- <li class="disabled">
                                        <a href="">
                                            <i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="15" height="15" /></i>
                                            <?php echo genLabel('LBL_MORE_INFO') ?>
                                        </a>
                                    </li> -->
                                    <li>
                                        <a href="javascript:void(0)" onclick="$.clearAll();" style="color: #feb018;">
                                            <i><img src="<?php echo site_assets_url('images/icons/clearo.png'); ?>" width="15" height="17" /></i>
                                            <?php echo genLabel('LBL_CLEAR') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" id="adsearch">
                                            <i><img src="<?php echo site_assets_url('images/icons/advancesearchb.png'); ?>" width="20" height="15" /></i>
                                            <?php echo genLabel('LBL_ADVANCE_SEARCH') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 align-self-center">
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="status1" role="tabpanel">
                                    <div class="row">
                                        <input id="action" type="hidden" name="action">
                                        <div class="col-sm-12" style="background-color: #fff;">
                                            <div class="row" style="margin-top: -15px;">
                                                <input id="contactid" name="contactid" type="hidden" />
                                                <div class="col-sm-6">
                                                    <div class="form-group m-t-5 row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_CONTACT_CODE') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="contact_no" id="contact_code" readonly />
                                                        </div>
                                                       </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group m-t-5 row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_STATUS') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <select id="contact_status" name="con_contactstatus" class="form-control select2" style="font-size: 11px; color:#2b2b2b;">
                                                                <option value="Active">Active</option>
                                                                <option value="In Active">In Active</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px;">
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <span style="font-size: 11px; font-family: PromptMedium; color: red;">*</span> <?php echo genLabel('LBL_FRIST_NAME') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="con_thainametitle" id="title_name">
                                                                        <option value="คุณ">คุณ</option>
                                                                        <option value="นาย">นาย</option>
                                                                        <option value="นางสาว">นางสาว</option>
                                                                        <option value="นาง">นาง</option>
                                                                    </select>
                                                                </div>
                                                                &nbsp;
                                                                <input type="text" class="form-control" aria-label="Text input with dropdown button" name="firstname" id="fristname_contact" required="true"/>
                                                            </div>
                                                            <!-- <p id="firstname_required" style="font-size: 5px; font-family: PromptMedium; color: red; margin-top: 10px;"></p> -->
                                                            <span id="firstname_required" style="font-size: 14px; font-family: PromptMedium; color: red;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_LAST_NAME') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="lastname" id="lastname_contact" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px;">
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <span style="font-size: 11px; font-family: PromptMedium; color: red;">*</span> <?php echo genLabel('LBL_PHONE') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="mobile" id="phone_contact" required="true"/>
                                                            <!-- <p id="phone_required" style="font-size: 5px; font-family: PromptMedium; color: red; margin-top: 10px;"></p> -->
                                                            <span id="phone_required" style="font-size: 14px; font-family: PromptMedium; color: red;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_EMAIL') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="email" id="email_contact" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px;">
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_LINE_ID') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="line_id" id="lineid_contact" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_FACEBOOK') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="facebook" id="facebook_contact" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px;">
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_REMARK') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <textarea class="form-control" rows="1" name="remark" id="remark_contact"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_EMOTION') ?>
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <div class="row" style="margin-left: 1px;">
                                                                <label class="custom-control custom-radio">
                                                                    <input type="radio" name="emotion_details" id="natured" value="Natured" class="custom-control-input">
                                                                    <span class="custom-control-label"> 
                                                                        <i><img src="<?php echo site_assets_url('images/icons/emo01.png'); ?>" width="25" height="25" /></i>
                                                                    </span>
                                                                </label>
                                                                <label class="custom-control custom-radio" style="margin-left: 20px;">
                                                                    <input type="radio" name="emotion_details" id="normal" value="Normal" class="custom-control-input">
                                                                    <span class="custom-control-label">
                                                                        <i><img src="<?php echo site_assets_url('images/icons/emo02.png'); ?>" width="25" height="25" /></i>
                                                                    </span>
                                                                </label>
                                                                <label class="custom-control custom-radio" style="margin-left: 20px;">
                                                                    <input type="radio" name="emotion_details" id="morose" value="Morose" class="custom-control-input">
                                                                    <span class="custom-control-label">
                                                                        <i><img src="<?php echo site_assets_url('images/icons/emo03.png'); ?>" width="25" height="25" /></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                                <!-- <div class="row" style="margin-left: 1px;">
                                                                    <div class="col-sm-4">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="natured" id="natured" />
                                                                            <span class="custom-control-label">
                                                                                <i><img src="<?php echo site_assets_url('images/icons/emo01.png'); ?>" width="25" height="25" /></i>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="normal" id="normal" />
                                                                            <span class="custom-control-label">
                                                                                <i><img src="<?php echo site_assets_url('images/icons/emo02.png'); ?>" width="25" height="25" /></i>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="morose" id="morose" />
                                                                            <span class="custom-control-label">
                                                                                <i><img src="<?php echo site_assets_url('images/icons/emo03.png'); ?>" width="25" height="25" /></i>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px;">
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_SITE_CODE') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <select id="sitecode_contact" name="contact_type_details" class="form-control select2" style="font-size: 11px; color: #2b2b2b;">
                                                                <option value="--None--">--None--</option>
                                                                <option value="Owner">Owner</option>
                                                                <option value="Contractor-Company">Contractor-Company</option>
                                                                <option value="Contractor-Individual">Contractor-Individual</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; text-align: right;">
                                                            <?php echo genLabel('LBL_DATE_UPDATE') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="register_date" id="dateupdate_contact" disabled="disabled" style="background-color: #fff;" data-date-format="dd/mm/yyyy" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px;">
                                                <div class="col-sm-12">
                                                    <button type="button" class="btn btn-success d-none d-lg-block m-l-15 btncallin" id="searching" style="font-size: 11px;">
                                                        <i><img src="<?php echo site_assets_url('images/icons/searching.png'); ?>" width="18" height="15" /></i>
                                                            <?php echo genLabel('LBL_SEARCHING') ?>
                                                    </button>
                                                        <!-- The Modal -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- card -->
            &nbsp;
            <div class="card o-income" style="border-radius: 0px 0px 5px 5px;">
                <div class="card-body">
                    <div class="page-titles d-flex align-items-center no-block">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="" style="margin-top: 3px; cursor: default; pointer-events: none; text-decoration: none;">
                                    <span class="hidden-xs-down"><?php echo genLabel('LBL_BILL_TO_INFORMATION') ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="ml-auto" style="margin-top: 2px;">
                            <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                                <!-- <li class="disabled">
                                    <a href="">
                                        <i><img src="<?php echo site_assets_url('images/icons/statusb.png'); ?>" width="15" height="15" /></i>
                                        <?php echo genLabel('LBL_STATUS') ?>
                                    </a>
                                </li> -->
                               <!--  <li style="background: #785dd0;">
                                    <a href="javascript:void(0)" style="color: #ffffff;">
                                        <i><img src="<?php echo site_assets_url('images/icons/copyb.png'); ?>" width="15" height="15" /></i>
                                        <?php echo genLabel('LBL_COPY') ?>
                                    </a>
                                </li> -->
                                <!-- <li class="disabled">
                                    <a href="">
                                        <i><img src="<?php echo site_assets_url('images/icons/copyb.png'); ?>" width="15" height="15" /></i>
                                        <?php echo genLabel('LBL_COPY') ?>
                                    </a>
                                </li> -->
                                <!-- <li class="disabled">
                                    <a href="">
                                        <i><img src="<?php echo site_assets_url('images/icons/advancesearchb.png'); ?>" width="20" height="15" /></i>
                                        <?php echo genLabel('LBL_ADVANCE_SEARCH') ?>
                                    </a>
                                </li> -->
                                <li>
                                    <a href="javascript:void(0)" onclick="$.clearAllBill();" style="color: #feb018;">
                                        <i><img src="<?php echo site_assets_url('images/icons/clearo.png'); ?>" width="15" height="17" /> </i>
                                        <?php echo genLabel('LBL_CLEAR') ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-12 align-self-center">
                        <div class="tab-content tabcontent-border">
                            <div class="tab-pane active" id="status1" role="tabpanel">
                                <form>
                                    <div class="form-group row" style="margin-top: -10px;">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_COMPANY_NAME') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control formbill" type="text" name="companyname" id="companyname" readonly />
                                        </div>
                                        <input id="accountid" type="hidden" name="accountid" />
                                    </div>
                                </form>
                                <form style="margin-top: -25px;">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_BRACH') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control formbill" type="text" name="brach" id="brach" readonly />
                                        </div>
                                    </div>
                                </form>
                                <form style="margin-top: -25px;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_TAX_NO') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input class="form-control formbill" type="text" name="taxno" id="taxno" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_TELEPHONE') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input class="form-control formbill" type="text" name="telephone" id="telephone" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form style="margin-top: -25px;">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_ADDRESS') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control formbill" rows="1" name="address" id="address" readonly></textarea>
                                        </div>
                                    </div>
                                </form>
                                <form style="margin-top: -25px;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_CONTACT_PERSON') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input class="form-control formbill" type="text" name="contact_person" id="contact_person" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    Contact Tel.
                                                </label>
                                                <div class="col-sm-8">
                                                    <input class="form-control formbill" type="text" name="contact_tel" id="contact_tel" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form style="margin-top: -25px;">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_ADDRESS_BILL_TO') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control formbill" rows="1" name="address_bill_to" id="address_bill_to" readonly></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-group" style="margin-bottom: 40px;">
            <!-- card -->
            <div class="card o-income" style="border-radius: 0px 0px 5px 5px;">
                <div class="card-body">
                    <form id="form_save_case" name="form_save_case" method="POST" enctype="multipart/form-data" >
                        <div class="page-titles d-flex align-items-center no-block">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="" style="margin-top: 3px; cursor: default; pointer-events: none; text-decoration: none;">
                                        <span class="hidden-xs-down"><?php echo genLabel('LBL_CASE_DETAIL') ?></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="ml-auto" style="margin-top: 2px;">
                                <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                                    <li style="background: #018ffb;">
                                        <a href="javascript:void(0)" onclick="$.saveCase();" style="color: #ffffff;" >
                                            <i><img src="<?php echo site_assets_url('images/icons/savew.png'); ?>" width="15" height="15" /></i>
                                            <?php echo genLabel('LBL_SAVE') ?>
                                        </a>
                                    </li>
                                    <!-- <li class="disabled">
                                        <a href="">
                                            <i><img src="<?php echo site_assets_url('images/icons/smsb.png'); ?>" width="18" height="15" /></i>
                                            <?php echo genLabel('LBL_SMS') ?>
                                        </a>
                                    </li> -->
                                    <!-- <li>
                                        <a href="javascript:void(0)">
                                            <i><img src="<?php echo site_assets_url('images/icons/Email.png'); ?>" width="18" height="15" /></i>
                                            <?php echo genLabel('LBL_EMAIL') ?>
                                        </a>
                                    </li> -->
                                    <!-- <li class="disabled">
                                        <a href="">
                                            <i><img src="<?php echo site_assets_url('images/icons/callInb.png'); ?>" width="15" height="15" /></i>
                                            <?php echo genLabel('LBL_CALL_IN') ?>
                                        </a>
                                    </li> -->
                                    <li>
                                        <a href="javascript:void(0)" onclick="$.clearAllCase();" style="color: #feb018;">
                                            <i><img src="<?php echo site_assets_url('images/icons/clearo.png'); ?>" width="15" height="17" /> </i>
                                            <?php echo genLabel('LBL_CLEAR') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 align-self-center">
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="status1" role="tabpanel">
                                    <!-- <div class="form-group row" style="margin-top: -10px;">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_CASE_ID') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <input class="form-control" disabled="disabled" type="text" name="ticketid" id="ticketid" style="background-color: #fff;" />
                                        </div>
                                    </div> -->
                                    <input type="hidden" name="tel" id="tel_savecase">
                                    <input type="hidden" name="email" id="email_savecase">
                                    <input type="hidden" name="line_id" id="line_id_savecase">
                                    <input type="hidden" name="facebook" id="facebook_savecase">
                                    <div class="row">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <span style="font-size: 11px; font-family: PromptMedium; color: red;">*</span> <?php echo genLabel('LBL_PROBLEM_NAME') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="task_name" id="task_name" onchange="descrtioncase();" required="true">
                                                <option value=""></option>
                                                <option value="ลูกค้าเปิด Order">ลูกค้าเปิด Order</option>
                                                <option value="ลูกค้าแก้ไข Order">ลูกค้าแก้ไข Order</option>
                                                <option value="ลูกค้าสอบถามราคา">ลูกค้าสอบถามราคา</option>
                                                <option value="ลูกค้าสอบถามเรื่องอื่นๆ">ลูกค้าสอบถามเรื่องอื่นๆ</option>
                                                <option value="ลูกค้าติดตามการจัดส่ง">ลูกค้าติดตามการจัดส่ง</option>
                                                <option value="ลูกค้าติดตามราคา">ลูกค้าติดตามราคา</option>
                                                <option value="ลูกค้าร้องเรียน">ลูกค้าร้องเรียน</option>
                                                <option value="ลูกค้าเลื่อนคิว">ลูกค้าเลื่อนคิว</option>
                                                <option value="ME เลื่อนคิว">ME เลื่อนคิว</option>
                                                <option value="ME แจ้งราคา">ME แจ้งราคา</option>
                                                <option value="ME แจ้งติดตามการจัดส่ง">ME แจ้งติดตามการจัดส่ง</option>
                                                <option value="ME แจ้งติดตามราคา">ME แจ้งติดตามราคา</option>
                                                <option value="ME สรุป">ME สรุป</option>
                                                <option value="อื่นๆ">อื่นๆ</option>
                                            </select>
                                            <!-- <p id="demo" style="font-size: 5px; font-family: PromptMedium; color: red; margin-top: 10px;"></p> -->
                                            <span id="demo" style="font-size: 14px; font-family: PromptMedium; color: red; "></span>
                                        </div>
                                        <!-- <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                                    <?php echo genLabel('LBL_PROBLEM_NAME') ?>
                                                </label>
                                                <div class="col-sm-9">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="task_name" id="task_name">
                                                        <option selected></option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                            </div>  
                                        </div> -->
                                        <!-- <div class="col-sm-6">
                                            <div class="form-group row">
                                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                                        <?php echo genLabel('LBL_SHOTCUT_CHANNEL') ?>
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="shotcut_name" id="shotcut_name">
                                                            <option selected></option>
                                                            <option>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                            </div>  
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_STATUS') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="case_status" id="case_status">
                                                        <option value="เปิดงาน">เปิดงาน</option>
                                                        <option value="ดำเนินการ">ดำเนินการ</option>
                                                        <option value="ปิดงาน">ปิดงาน</option>
                                                        <option value="ยกเลิก">ยกเลิก</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_CONTACT_CHANNEL') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="contact_channel" id="contact_channel">
                                                        <option>Call</option>
                                                        <option>Line</option>
                                                        <option>Facebook</option>
                                                        <option>Email</option>
                                                        <option>Chaeday</option>
                                                        <option>Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_RESPONSE') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="response" id="response">
                                                        <option>Call</option>
                                                        <option>Line</option>
                                                        <option>Facebook</option>
                                                        <option>Email</option>
                                                        <option>Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_HANDLED_BY') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <!-- <div class="input-group">
                                                        <input type="text" class="form-control" name="assigned_user_id" id="assigned_user_id" />
                                                        <div class="input-group-append" style="height: 25px;">
                                                            <span class="input-group-text">
                                                                <i><img src="<?php echo site_assets_url('images/icons/assign.png'); ?>" width="18" height="15" /></i>
                                                            </span>
                                                        </div>
                                                    </div> -->
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="smownerid" id="assigned_user_id">
                                                        <?php foreach ($assigned as $key => $value){  ?>

                                                            <option value="<? echo $value['id']?>" <? echo ($value['id'] == USERID) ? 'selected' : ''; ?> ><? echo $value['fullname']?></option>

                                                        <?php } ?>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_DUE_DATE') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input type="datetime-local" class="form-control" placeholder="" name="case_date" id="case_date" data-date="" data-date-format="dd/mm/yyyy H:i" value="<?php echo date('Y-m-d\TH:i'); ?>" />
                                                        <!-- <input type="text" class="form-control datepicker-autoclose" placeholder="" name="case_date" id="case_date" data-date-format="dd-mm-yyyy" />
                                                        <input type="text" name="case_date" id="case_date" class="form-control date-format" >
                                                        <input type="hidden" id="casedate" class="form-control date-format" data-date="" value="<?php echo date('d/m/Y\TH:i'); ?>"> -->
                                                        <!-- <div class="input-group-append" style="height: 25px;">
                                                            <span class="input-group-text">
                                                                <i><img src="<?php //echo site_assets_url('images/icons/calendar.png'); ?>" width="15" height="15" /></i>
                                                            </span> 
                                                        </div> -->
                                                    </div> 
                                              </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_CLOSE_DATE') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input type="datetime-local" class="form-control" name="date_completed" id="date_completed" data-date-format="dd/mm/yyyy H:i" />
                                                        <!-- <input type="text" class="form-control datepicker-autoclose" placeholder="" name="date_completed" id="date_completed" data-date-format="dd/mm/yyyy" />
                                                        <div class="input-group-append" style="height: 25px;">
                                                            <span class="input-group-text">
                                                                <i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="15" height="15" /></i>
                                                            </span>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_CASE_DESCRIPTION') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" rows="1" name="description" id="descrip_case"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_NOTE') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" rows="1" name="notes" id="notes" placeholder="เรื่องนอกเหนือจาก Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" for="customFile" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                            <?php echo genLabel('LBL_ATTACH_FILE') ?>
                                        </label>
                                        <div class="col-sm-10">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <!-- actual upload which is hidden -->
                                                    <!-- <input type="file" id="actual-btn" hidden /> -->
                                                    <!-- our custom upload button -->
                                                    <!-- <label for="actual-btn" class="btn btn-outline-secondary" style="font-size: 11px; color: #2b2b2b; border-radius: 5px; font-family: PromptMedium; height: 25px; line-height: 13px;">
                                                        Upload
                                                    </label> -->
                                                    <input type="file" id="file_upload" name="file_upload" hidden>
                                                    <label for="file_upload" class="btn btn-outline-secondary" style="font-size: 11px; color: #2b2b2b; border-radius: 5px; font-family: PromptMedium; height: 25px; line-height: 13px;">
                                                        Upload
                                                    </label>
                                                </div>

                                                <div class="col-sm-10">
                                                    <!-- <span class="form-control" id="file-chosen" contenteditable="false" style="font-size: 11px;"></span> -->
                                                    <!-- <label class="custom-file-label form-control" id="file-chosen">Choose file</label> -->
                                                    <input class="form-control" type="text" id="file-chosen" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="row" style="margin-top: -15px;">
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_CRATOR') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="handled_by" id="handled_by" disabled="disabled" style="background-color: #fff;" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                    <?php echo genLabel('LBL_DATE_CREATE') ?>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" type="text" name="case_date" id="case_date" disabled="disabled" style="background-color: #fff;" />
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- card -->
            &nbsp;
            <div class="card o-income" style="border-radius: 0px 0px 5px 5px;">
                <div class="card-body">
                    <div class="d-flex page-titles d-flex align-items-center no-block">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#status0" role="tab" aria-selected="true" style="margin-top: 3px;">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down"><?php echo genLabel('LBL_FAQ') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1" style="margin-top: 3px;">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down"><?php echo genLabel('LBL_KM') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu2" style="margin-top: 3px;">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down"><?php echo genLabel('LBL_HISTORY_CASE') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu3" style="margin-top: 3px;">
                                    <span class="hidden-sm-up"><i class="ti-email"></i></span>
                                    <span class="hidden-xs-down"><?php echo genLabel('LBL_HISTORY_ORDER') ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="ml-auto" style="margin-top: 2px;">
                            <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                                <!-- <li class="disabled">
                                    <a href="javascript:void(0)">
                                        <i><img src="<?php echo site_assets_url('images/icons/sendnoticeb.png'); ?>" width="15" height="15" /></i>
                                        <?php echo genLabel('LBL_SEND_NOTICE') ?>
                                    </a>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 align-self-center">
                        <div class="tab-content tabcontent-border">
                            <div class="tab-pane active" id="status0" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-12" style="background-color: #fff;">
                                        <form id="form_faq" action="#" method="POST">
                                            <!-- <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="select_faq" id="select_faq">
                                                        <option selected>Select</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="custom-file" style="margin-left: 5px;">
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" placeholder="Search Text" style="font-size: 11px; color: #2b2b2b;" name="faqname" id="faqname" />
                                                        &nbsp;
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary fillterfaq" type="button" style="font-size: 11px; color: #2b2b2b; border-radius: 10px;">
                                                                <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" />
                                                                <?php echo genLabel('LBL_SEARCH') ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </form>
                                        <div id="grid_faq"></div>
                                        <!-- <div class="col-12">
                                            <div class="table-responsive text-center">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr style="background: #EDEDED; font-size: 11px; color: #2B2B2B;">
                                                            <th>More Info</th>
                                                            <th>FAQ Type</th>
                                                            <th>FAQ Name</th>
                                                            <th>FAQ Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="font-size: 11px; color: #2B2B2B;">
                                                        <td>
                                                            <i class="fa fa-info-circle"></i>
                                                        </td>
                                                        <td>FAQ</td>
                                                        <td>ไม่ได้รับใบแจ้งหนี้/ใบเสร็จรับเงิน หรือได้รับล่าช้าเกินวันครบกำหนดไปแล้ว..?</td>
                                                        <td>16</td>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12" style="background-color: #fff;">
                                        <!-- <form id="form_km" action="#" method="POST">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="select_km" id="select_km">
                                                        <option selected>Select</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="custom-file" style="margin-left: 5px;">
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" placeholder="Search Text" style="font-size: 11px; color: #2b2b2b;" name="knowledgebasename" id="knowledgebasename" />
                                                        &nbsp;
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary fillterkm" type="button" style="font-size: 11px; color: #2b2b2b; border-radius: 10px;">
                                                                <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" />
                                                                <?php echo genLabel('LBL_SEARCH') ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form> -->
                                        <div id="grid_km"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="menu2" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12" style="background-color: #fff;">
                                        <form id="form_historycase" action="#" method="POST">
                                            <!-- <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="select_historycase" id="select_historycase">
                                                        <option selected>Select</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="custom-file" style="margin-left: 5px;">
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" placeholder="Search Text" style="font-size: 11px; color: #2b2b2b;" name="knowledgebasename" id="knowledgebasename" />
                                                        &nbsp;
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary fillterkm" type="button" style="font-size: 11px; color: #2b2b2b; border-radius: 10px;">
                                                                <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" />
                                                                <?php echo genLabel('LBL_SEARCH') ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </form>
                                        <div id="grid_historycase"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="menu3" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12" style="background-color: #fff;">
                                        <form id="form_historyorder" action="#" method="POST">
                                            <!-- <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b;" name="select_historycase" id="select_historyorder">
                                                        <option selected>Select</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="custom-file" style="margin-left: 5px;">
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" placeholder="Search Text" style="font-size: 11px; color: #2b2b2b;" name="ordername" id="ordername" />
                                                        &nbsp;
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary fillterkm" type="button" style="font-size: 11px; color: #2b2b2b; border-radius: 10px;">
                                                                <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" />
                                                                <?php echo genLabel('LBL_SEARCH') ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </form>
                                        <div id="grid_historyorder"></div>
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

<div id="modalAccountsearch" class="modal modal-popup">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2b2b2b;">
                <b><?php echo genLabel('LBL_ADVANCE_SEARCH') ?></b>
            </span>
            <span class="close" id="closeAccountsearch"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
        </div>
        <div class="modal-body" style="margin-top: 10px;">
            <div class="col-12">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-1" style="margin: auto;">
                            <p style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">
                                <?php echo genLabel('LBL_SEARCH_CONDITIONS') ?>
                            </p>
                        </div>
                        <div class="col-sm" style="border: 1px solid #ededed; border-radius: 5px;">
                            <form id="form_account" action="#" method="POST">
                                <div class="row" style="padding-top: 20px; padding-left: 20px;">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2b2b2b;"><?php echo genLabel('LBL_FIRSTNAME_ACCOUNT') ?>:</label>
                                                    <div class="col-sm">
                                                        <input class="form-control formaccountsearch" name="firstname" id="firstname" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                        <?php echo genLabel('LBL_CONTACT_NO') ?>
                                                        :
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control formaccountsearch" name="contactno" id="contactno" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -25px;">
                                            <div class="col-sm">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2b2b2b;"><?php echo genLabel('LBL_PHONE_ACCOUNT') ?>:</label>
                                                    <div class="col-sm">
                                                        <input class="form-control formaccountsearch" name="mobile" id="mobile" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                        <?php echo genLabel('LBL_LINE_ID') ?>
                                                        :
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control formaccountsearch" name="line_id" id="line_id" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2b2b2b;">
                                                        <?php echo genLabel('LBL_FACEBOOK') ?>
                                                        :
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control formaccountsearch" name="facebook" id="facebook" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2" style="margin-top: 10px;">
                                        <button type="button" class="btn btn-success d-none d-lg-block btncallin" onclick="$.filltercontact();" style="float: none;">
                                            <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" /></i>
                                            <?php echo genLabel('LBL_SEARCH') ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row m-t-30">
                        <!-- <div class="col-sm-12">
                            <p style="text-align: center; font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">ค้นพบออเดอร์ทั้งหมด 8 รายการ</p>
                        </div> -->
                        <div class="col-sm-12">
                            <div id="grid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalSearching" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <!-- <span class="close text-right">&times;</span> -->
        <!-- <span class="close text-right"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span> -->
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2b2b2b;">
                <b><?php echo genLabel('LBL_SEARCHING_MODAL') ?></b>
            </span>
            <span class="close" id="closesearching"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
            <br />
        </div>
        <div class="modal-body" style="margin-top: 10px;">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div id="googleMap" style="width: 100%; height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6 colsm6searching">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input
                                                                class="form-control formsearching"
                                                                type="text"
                                                                placeholder="<?php echo genLabel('LBL_CONTACT_CODE') ?>"
                                                                style="font-size: 11px;"
                                                                name="contactcode_searching"
                                                                id="contactcode_searching"
                                                                readonly
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input
                                                                class="form-control formsearching"
                                                                type="text"
                                                                style="font-size: 11px;"
                                                                name="contactdate_searching"
                                                                id="contactdate_searching"
                                                                value="<?php echo date('d/m/Y'); ?>"
                                                                readonly
                                                            /><!-- placeholder="<?php echo genLabel('LBL_CONTACT_DATE') ?>" -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-left: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input
                                                                class="form-control formsearching"
                                                                type="text"
                                                                placeholder="<?php echo genLabel('LBL_CONTACT_NAME') ?>"
                                                                style="font-size: 11px;"
                                                                name="contactname_searching"
                                                                id="contactname_searching"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input
                                                                class="form-control formsearching"
                                                                type="text"
                                                                placeholder="<?php echo genLabel('LBL_CONTACT_TEL') ?>"
                                                                style="font-size: 11px;"
                                                                name="contacttel_searching"
                                                                id="contacttel_searching"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-left: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_LINE_ID') ?>" style="font-size: 11px;" name="lineid_searching" id="lineid_searching" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input
                                                                class="form-control formsearching"
                                                                type="text"
                                                                placeholder="<?php echo genLabel('LBL_FACEBOOK') ?>"
                                                                style="font-size: 11px;"
                                                                name="facebook_searching"
                                                                id="facebook_searching"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-left: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input
                                                                class="form-control formsearching"
                                                                type="text"
                                                                placeholder="<?php echo genLabel('LBL_PROJECT_ADDRESS') ?>"
                                                                style="font-size: 11px;"
                                                                name="projectaddress_searching"
                                                                id="projectaddress_searching"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 colsm6searching">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <div class="input-group">
                                                                <input
                                                                    type="text"
                                                                    class="form-control formsearching"
                                                                    placeholder="<?php echo genLabel('LBL_LOCATION') ?>"
                                                                    style="font-size: 11px;"
                                                                    name="location_searching"
                                                                    id="location_searching" 
                                                                />
                                                                <input id="latitudeplant" name="latitudeplant" type="hidden">
                                                                <input id="longitudeplant" name="longitudeplant" type="hidden">
                                                                <input id="cityplant" name="cityplant" type="hidden">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" style="height: 30px; background-color: #ffffff; border: 0px;">
                                                                        <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" /></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-left: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <select class="form-control formsearching" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;" name="trucksize" id="trucksize" onchange="getplantnotlocation()">
                                                                <option value="รถใหญ่" selected>รถใหญ่</option>
                                                                <option value="รถเล็ก">รถเล็ก</option>
                                                                <option value="รถใหญ่ 5 คิว">รถใหญ่ 5 คิว</option>
                                                                <option value="รถเล็ก 2 คิว">รถเล็ก 2 คิว</option>
                                                                <option value="ใหญ่ 5 คิว">ใหญ่ 5 คิว</option>
                                                                <option value="ใหญ่ 6 คิว">ใหญ่ 6 คิว</option>
                                                            </select>
                                                            <!-- <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_TRUCK_SIZE') ?>" name="trucksize" id="trucksize"> -->
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_QUEUE_QTY') ?>" style="font-size: 11px;" name="queueqty" id="queueqty" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-right: 1px solid #ededed; border-left: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <select class="form-control formsearching" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;" name="mattype" id="mattype" onchange="getplantnotlocation()">
                                                                <option value="ทั่วไป" selected>ทั่วไป</option>
                                                                <option value="กันซึม">กันซึม</option>
                                                                <option value="เททับหน้า">เททับหน้า</option>
                                                                <option value="เข็มเจาะเล็ก">เข็มเจาะเล็ก</option>
                                                            </select>
                                                            <!-- <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_MAT_TYPE') ?>" name="mattype" id="mattype"> -->
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <!-- <select class="form-control formsearching" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;" name="strengrh" id="strengrh">
                                                                <option><?php echo genLabel('LBL_STRENGTH') ?></option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select> -->
                                                            <!-- <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_STRENGTH') ?>" name="strengrh" id="strengrh"> -->
                                                            <select select class="select2 m-b-10 select2-multiple" multiple="multiple" data-placeholder="Choose" style="width: 100%;" name="strength" id="strength" onchange="getplantnotlocation()">
                                                                <option value="180 กก./ตร.ซม." selected >180 กก./ตร.ซม.</option>
                                                                <option value="210 กก./ตร.ซม.">210 กก./ตร.ซม.</option>
                                                                <option value="240 กก./ตร.ซม.">240 กก./ตร.ซม.</option>
                                                                <option value="280 กก./ตร.ซม.">280 กก./ตร.ซม.</option>
                                                                <option value="300 กก./ตร.ซม.">300 กก./ตร.ซม.</option>
                                                                <option value="320 กก./ตร.ซม.">320 กก./ตร.ซม.</option>
                                                                <option value="350 กก./ตร.ซม.">350 กก./ตร.ซม.</option>
                                                                <option value="380 กก./ตร.ซม.">380 กก./ตร.ซม.</option>
                                                                <option value="400 กก./ตร.ซม.">400 กก./ตร.ซม.</option>
                                                                <option value="420 กก./ตร.ซม.">420 กก./ตร.ซม.</option>
                                                                <option value="450 กก./ตร.ซม.">450 กก./ตร.ซม.</option>
                                                                <option value="500 กก./ตร.ซม.">500 กก./ตร.ซม.</option>
                                                                <option value="Lean Concrete">Lean Concrete</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-right: 1px solid #ededed; border-left: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <select class="form-control formsearching" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;" name="objective" id="objective">
                                                                <option value="เทพื้น">เทพื้น</option>
                                                                <option value="เทคาน">เทคาน</option>
                                                                <option value="เทเสา">เทเสา</option>
                                                                <option value="อื่นๆ">อื่นๆ</option>
                                                            </select>
                                                            <!-- <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_OBJECTIVE') ?>" name="objective" id="objective"> -->
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #ededed; border-right: 1px solid #ededed;">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input type="datetime-local" class="form-control" placeholder="<?php echo genLabel('LBL_USAGE_DATE') ?>" name="usage_date" id="usage_date" data-date="" data-date-format="dd/mm/yyyy HH:mm" />
                                                            <!-- <div class="input-group">
                                                                 
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                        <i><img src="<?php echo site_assets_url('images/icons/Calendar.png'); ?>" width="15" height="15" /></i>
                                                                    </span>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 colsm6searching">
                                                        <div class="col-sm-12 m-t-5 colsm12searching">
                                                            <input
                                                                class="form-control formsearching"
                                                                type="text"
                                                                placeholder="<?php echo genLabel('LBL_DESCRTION') ?>"
                                                                style="font-size: 11px;"
                                                                name="descrtion_searching"
                                                                id="descrtion_searching"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 m-t-5" style="text-align: right;">
                                                        <button id="savesearchingbtn" type="button" class="btn btn-success d-none d-lg-block m-l-15 btnsavesearching">
                                                            <i><img src="<?php echo site_assets_url('images/icons/savebuttono.png'); ?>" width="15" height="15" /></i>
                                                            <?php echo genLabel('LBL_CREATE_ORDER') ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h4 style="color: #2b2b2b; font-family: PromptMedium; font-size: 12px;">
                                    <?php echo genLabel('LBL_VIP_PARTNER') ?>
                                </h4>
                                <div id="grid_vippartner"></div>
                                <!-- <div class="table-responsive text-center">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background: #EDEDED; font-family: PromptMedium; color: #2B2B2B; font-size: 12px;">
                                            <th>เลือก</th>
                                            <th>แพล้นท์</th>
                                            <th>ห่าง</th>
                                            <th>ประเภท</th>
                                            <th>ST</th>
                                            <th>กำไร</th>
                                            <th>ขาย</th>
                                            <th>รหัส</th>
                                            <th>ขนาดรถ</th>
                                            <th>LP</th>
                                            <th>ลด(%)</th>
                                            <th>ทุน+VAT</th>
                                            <th>ค่าส่ง</th>
                                            <th>Into</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-family: PromptMedium; font-size: 11px; color: #2B2B2B;">
                                        <td class="centered custom-checkbox">
                                            <input type="checkbox" style="text-align:center;">
                                        </td>
                                        <td>
                                            นำเฮง มักกะสัน
                                        </td>
                                        <td>
                                            5.1
                                        </td>
                                        <td>
                                            ทั่วไป
                                        </td>
                                        <td>
                                            180
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            1950.00
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            ใหญ่ 5 คิว
                                        </td>
                                        <td>
                                            2580.00
                                        </td>
                                        <td>
                                            34.00
                                        </td>
                                        <td>
                                            1822.00
                                        </td>
                                        <td>
                                            535 / คิว
                                        </td>
                                        <td class="text-center">
                                            <i class="fa fa-info-circle">
                                            </i>
                                        </td>
                                    </tbody>
                                </table>
                            </div> -->
                            </div>
                            <div class="col-12 m-t-10">
                                <h4 style="color: #2b2b2b; font-family: PromptMedium; font-size: 12px;">
                                    <?php echo genLabel('LBL_PARTNER') ?>
                                </h4>
                                <div id="grid_partner"></div>
                                <!-- <div class="table-responsive text-center">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background: #EDEDED; color: #2B2B2B; font-size: 12px;">
                                            <th>เลือก</th>
                                            <th>แพล้นท์</th>
                                            <th>ห่าง</th>
                                            <th>ประเภท</th>
                                            <th>ST</th>
                                            <th>กำไร</th>
                                            <th>ขาย</th>
                                            <th>รหัส</th>
                                            <th>ขนาดรถ</th>
                                            <th>LP</th>
                                            <th>ลด(%)</th>
                                            <th>ทุน+VAT</th>
                                            <th>ค่าส่ง</th>
                                            <th>Info</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 11px; color: #2B2B2B;">
                                        <td class="centered custom-checkbox">
                                            <input type="checkbox" style="text-align:center;">
                                        </td>
                                        <td>
                                            นำเฮง มักกะสัน
                                        </td>
                                        <td>
                                            5.1
                                        </td>
                                        <td>
                                            ทั่วไป
                                        </td>
                                        <td>
                                            180
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            1950.00
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            ใหญ่ 5 คิว
                                        </td>
                                        <td>
                                            2580.00
                                        </td>
                                        <td>
                                            34.00
                                        </td>
                                        <td>
                                            1822.00
                                        </td>
                                        <td>
                                            535 / คิว
                                        </td>
                                        <td>
                                            <i class="fa fa-info-circle">
                                            </i>
                                        </td>
                                    </tbody>
                                </table>
                            </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-dialog-full-width modal-dialog momodel modal-fluid" role="document">
        <div class="modal-content-full-width modal-content" style="width: 92%; padding-right: 0px; padding-left: 0px;">
            <div class="modal-header">
                <span style="font-size: 16px; font-family: PromptMedium; color: #2b2b2b;">
                    <b><?php echo genLabel('LBL_ORDER_MANAGEMENT') ?></b>
                </span>
                <span class="close" id="closeordermanagement"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
                <br />
            </div>
            <div class="modal-body" style="margin-top: 10px;">
                <div class="col-12">
                    <div class="d-flex align-items-center no-block">
                        <ul class="nav nav-tabs" role="tablist" style="font-family: PromptMedium; border-bottom: 0px; padding-left: 15px;">
                            <li class="nav-item">
                                <a
                                class="nav-link active"
                                data-toggle="tab"
                                href="#bi"
                                style="font-size: 12px; border-color: #e5e5e5 #e5e5e5 #fff; background-color: #ffffff; color: #2b2b2b; padding-left: 50px; padding-top: 15px; padding-right: 50px;"
                                >
                                <b><?php echo genLabel('LBL_BASIC_INFORMATION') ?></b>
                            </a>
                        </li>
                    </ul>
                    <div class="ml-auto" style="margin-top: 0px; margin-bottom: 5px;">
                        <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                            <li style="box-shadow: none;">
                                <!-- <a href="#">
                                       Save
                                   </a> -->
                                   <button type="button" class="btn btn-success d-none d-lg-block save" onclick="$.saveOrder();">
                                    <b><?php echo genLabel('LBL_SAVE') ?></b>
                                </button>
                            </li>
                            <li style="box-shadow: none;">
                                <!-- <a href="#">
                                       Cancel
                                   </a> -->
                                   <button type="button" class="btn d-none d-lg-block cancel" onclick="$.cancalOrder();">
                                    <b><?php echo genLabel('LBL_CANCAL') ?></b>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content" style="box-shadow: 1px 0px 10px #e5e5e5; background-color: #ffffff;">
                    <div id="bi" class="tab-pane active">
                        <div class="col-12">
                            <div class="card-body">
                                <div class="row">
                                    <div class="container demo col-sm-12">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <form id="form_save_order" action="#" method="POST">
                                                <input id="accountid" type="hidden" name="accountid" />
                                                <input id="contactid" name="contactid" type="hidden" />
                                                <!-- <input id="plantid" name="plantid" type="hidden" /> -->
                                                <input id="plant_name" name="plant_name" type="hidden" />
                                                <input id="pricelist_no" name="pricelist_no" type="hidden" />
                                                <input id="project_addressorder" name="project_address" type="hidden" />
                                                <input id="truck_size_order" type="hidden" name="truck_size_order" />
                                                <input id="mat_type_order" type="hidden" name="mat_type_order" />
                                                <input id="descrtion_order" type="hidden" name="descrtion_order" />
                                                <input id="profit" type="hidden" name="profit" />
                                                <input id="strength_order" type="hidden" name="strength_order" />
                                                <!-- <input id="plant_id" name="plant_id" type="hidden" /> -->
                                                <div class="panel panel-default">
                                                    <div class="panel-headingom panel-clrom on active" role="tab" id="headingOne">
                                                        <div class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                &nbsp;<?php echo genLabel('LBL_ORDER_INFORMATION') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse panel-collapseom collapse show" role="tabpanel" aria-labelledby="headingOne">
                                                        <div class="panel-body panel-bodyom">
                                                            <div class="container">
                                                                <div class="col-sm-12">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group m-t-5 row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom"><?php echo genLabel('LBL_ORDER_NO') ?> </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" name="order_no" id="order_no" readonly />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group m-t-5 row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom"><?php echo genLabel('LBL_ORDER_DATE') ?></label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <!-- <div class="input-group">
                                                                                        <input type="text" class="form-control form-controlom datepicker-autoclose" name="order_date" id="order_date" data-date-format="dd/mm/yyyy" value="<?php echo date('d/m/Y'); ?>" />
                                                                                        <div class="input-group-append" style="height: 25px;">
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10" /> </i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div> -->
                                                                                    <input type="date" class="form-control form-controlom" name="order_date" id="order_date">  <br>
                                                                                    <!-- <input type="date" id="checkOutDate" disabled> -->
                                                                                    <small class="form-control-feedback">(dd/mm/yyyy)</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -40px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom"><?php echo genLabel('LBL_ORDER_STATUS') ?></label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <select class="form-control form-controlom" id="order_status_order" name="order_status_order">
                                                                                        <option>Open</option>
                                                                                        <option>Wait Vendor</option>
                                                                                        <option>Wait Confirm</option>
                                                                                        <option>Customer Payment</option>
                                                                                        <option>Wait Delivery</option>
                                                                                        <option>Start Delivery</option>
                                                                                        <option>Completed</option>
                                                                                        <option>Completed</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6"></div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Completed Sub Status</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <select class="form-control form-controlom" name="completed_sub_status_order" id="completed_sub_status_order">
                                                                                        <option>No Detail</option>
                                                                                        <option>Completed-CN with Payment</option>
                                                                                        <option>Completed-CN without Payment</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Completed Remark</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="completed_remark" id="completed_remark" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Lost Reason</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <select class="form-control form-controlom" name="lost_reason_order" id="lost_reason_order">
                                                                                        <option>--None--</option>
                                                                                        <option>ราคา</option>
                                                                                        <option>ตอบช้า</option>
                                                                                        <option>วันเวลาการส่งไม่ตรง</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_ACCOUNT_NAME') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="account_name" id="account_name" class="form-control form-controlom" readonly>
                                                                                    <!-- <div class="input-group">
                                                                                        <input type="text" class="form-control form-controlom" placeholder="" name="account_name" id="account_name" />
                                                                                        <div class="input-group-append" style="height: 25px;">
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10" /></i> 
                                                                                                <i class="fa fa-minus"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div> -->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    Contact Name
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="contact_name" id="contact_name" class="form-control form-controlom" readonly>
                                                                                    <!-- <div class="input-group">
                                                                                        <input type="text" class="form-control form-controlom" placeholder="" name="contact_name" id="contact_name" />
                                                                                        <div class="input-group-append" style="height: 25px;">
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10" /></i> 
                                                                                                <i class="fa fa-minus"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div> -->
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row" style="margin-top: -25px;">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Contact ID.</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="contact_no" id="contact_no_order" class="form-control form-controlom" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    Address
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <textarea class="form-control form-controlom" rows="2" name="address" id="address_order"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Telephone</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="telephone" id="telephone_order" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Sales Name</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="sales_name" id="sales_name" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Objective</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <select class="form-control form-controlom" name="objective_order" id="objective_order">
                                                                                        <option>เทพื้น</option>
                                                                                        <option>เทคาน</option>
                                                                                        <option>เทเสา</option>
                                                                                        <option>อื่นๆ</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Sales Tel</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="sales_tel" id="sales_tel" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Mix Easy Site Code</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="mix_easy_site_code" id="mix_easy_site_code" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Assigned To</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <select class="form-control select2" style="font-size: 11px; color: #2b2b2b; width: 100%;" name="smownerid" id="assigned_to_order">
                                                                                        <?php foreach ($assigned as $key => $value){  ?>

                                                                                            <option value="<? echo $value['id']?>" <? echo ($value['id'] == USERID) ? 'selected' : ''; ?> ><? echo $value['fullname']?></option>

                                                                                        <?php } ?>

                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Vendor Site code</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="vendor_site_code" id="vendor_site_code" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Plant ID</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="hidden" name="plantid" id="plantid" class="form-control form-controlom" />
                                                                                    <input type="text" name="plant_id" id="plant_id" class="form-control form-controlom" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Queue qty.</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="queue_qty" id="queue_qty" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                        </div>
                                                                    </div>
                                                                    <!--  <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_VENDOR_HQ') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_ADDRESS') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <textarea class="form-control form-controlom" rows="3"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_TAXPAYER_IDENTIFICATION_NO') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row" style="margin-top: -30px;">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_TEL') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_CONTACT_NAME') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control form-controlom" placeholder="" />
                                                                                        <div class="input-group-append" style="height: 25px;">
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_SALES') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_CONTACT_NO') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_MIX_EASY_SITE_CODE') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_SALES_TEL') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-sm-6"></div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px; margin-bottom: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <form class="form">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    <?php echo genLabel('LBL_VENDOR_SITE_CODE') ?>
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input class="form-control form-controlom" type="text" />
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-sm-6"></div>
                                                                </div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-headingom panel-clrom" role="tab" id="headingTwo">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            &nbsp;<?php echo genLabel('LBL_SHIPPING_ADDRESS') ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapseTwo" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                    <div class="panel-body panel-bodyom">
                                                        <div class="container">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label class="col-sm control-label col-form-label col-form-labelom">Delivery location</label>
                                                                                    <div class="col-sm-9 col-sm-9om">
                                                                                        <input type="text" name="delivery_location" id="delivery_location_order" class="form-control form-controlom" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label class="col-sm control-label col-form-label col-form-labelom">Province</label>
                                                                                    <div class="col-sm-9 col-sm-9om">
                                                                                        <input type="text" name="province_order" id="province_order" class="form-control form-controlom" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label class="col-sm control-label col-form-label col-form-labelom">Site Person</label>
                                                                                    <div class="col-sm-9 col-sm-9om">
                                                                                        <input type="text" name="site_person" id="site_person" class="form-control form-controlom" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label class="col-sm control-label col-form-label col-form-labelom">Site Phone (delivery)</label>
                                                                                    <div class="col-sm-9 col-sm-9om">
                                                                                        <input type="text" name="site_phone_delivery" id="site_phone_delivery" class="form-control form-controlom" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group row">
                                                                                    <label class="col-sm control-label col-form-label col-form-labelom">Fax (delivery)</label>
                                                                                    <div class="col-sm-9 col-sm-9om">
                                                                                        <input type="text" name="fax_delivery" id="fax_delivery" class="form-control form-controlom" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                               
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-headingom panel-clrom" role="tab" id="headingBill">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseBill" aria-expanded="false" aria-controls="collapseBill">
                                                            &nbsp;Bill to Address
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapseBill" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingBill">
                                                    <div class="panel-body panel-bodyom">
                                                        <div class="container">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Billing Name</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="billing_name" id="billing_name" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <!-- <label class="col-sm control-label col-form-label col-form-labelom">Vendor HQ</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="vendor_hq" id="vendor_hq" class="form-control form-controlom" />
                                                                            </div> -->
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Tax Address</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="tax_address" id="tax_address" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <!-- <label class="col-sm control-label col-form-label col-form-labelom">Tax Address</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="tax_address" id="tax_address" class="form-control form-controlom" />
                                                                            </div> -->
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Mailing Address</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="mailing_address" id="mailing_address" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Taxpayer Identification No. (bill to)</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="taxpayer_identification_no_bill_to" id="taxpayer_identification_no_bill_to" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Corporate registration number (CRN)</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="corporate_registration_number_crn" id="corporate_registration_number_crn" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Phone (bill to)</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="phone_bill_to" id="phone_bill_to" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Fax (bill to)</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="fax_bill_to" id="fax_bill_to" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Contact Person</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="contact_person" id="contact_person_order" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Contact Tel.</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="contact_tel" id="contact_tel_order" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-headingom panel-clrom" role="tab" id="headingThree">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                            &nbsp;<?php echo genLabel('LBL_TERMS_CONDITIONS') ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapseThree" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingThree">
                                                    <div class="panel-body panel-bodyom">
                                                        <div class="container">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-2 control-label col-form-label col-form-labelom" style="margin-right: -47px;">Validity</label>
                                                                            <div class="col-sm-10 col-sm-9om">
                                                                                <!-- <input type="text" name="validity" id="validity" class="form-control form-controlom" /> -->
                                                                                <input type="date" name="validity" id="validity" class="form-control form-controlom">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-2 control-label col-form-label col-form-labelom" style="margin-right: -47px;">Description</label>
                                                                            <div class="col-sm-10 col-sm-9om">
                                                                                <textarea class="form-control form-controlom" rows="3" name="description" id="description">* ลูกค้าจะต้องดูแลเส้นทางเข้า-ออกหน้างานให้รถขนส่งคอนกรีตเข้าถึงจุดเทคอนกรีตได้โดยสะดวก กรณีรถขนส่งคอนกรีตเข้าเทคอนกรีตไม่ได้ ลูกค้าจะต้องรับผิดชอบค่าคอนกรีตในเที่ยวนั้น
                                                                                </textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-2 control-label col-form-label col-form-labelom" style="margin-right: -47px;">Term and Condition</label>
                                                                            <div class="col-sm-10 col-sm-9om">
                                                                                <textarea class="form-control form-controlom" rows="3" name="term_and_condition" id="term_and_condition"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-headingom panel-clrom" role="tab" id="headingPlantouse">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePlantouse" aria-expanded="false" aria-controls="collapsePlantouse">
                                                            &nbsp;Plan to Use
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapsePlantouse" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingPlantouse">
                                                    <div class="panel-body panel-bodyom">
                                                        <div class="container">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Plan Date</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control form-controlom datepicker-autoclose" name="plan_date" id="plan_date" data-date-format="dd/mm/yyyy" />
                                                                                    <div class="input-group-append" style="height: 25px;">
                                                                                        <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                            <i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10" /> </i>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Plan Time</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input class="form-control" type="time" id="startTime">
                                                                                <input id="plan_time" type="hidden" name="plan_time">
                                                                                <!-- <input class="form-control" id="single-input" value="" placeholder="Now">-->
                                                                                <!-- <input type="text" name="plan_time" id="plan_time" class="form-control form-controlom" /> -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-headingom panel-clrom" role="tab" id="headingPaymentD">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePaymentD" aria-expanded="false" aria-controls="collapsePaymentD">
                                                            &nbsp;Payment - Display Order
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapsePaymentD" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingPaymentD">
                                                    <div class="panel-body panel-bodyom">
                                                        <div class="container">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Payment Method Name</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="payment_method_name" id="payment_method_name" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Bank</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <select class="form-control form-controlom" name="bank_order" id="bank_order">
                                                                                    <option value="--None--">
                                                                                        --None--
                                                                                    </option>
                                                                                    <option value="ธ.กรุงเทพ">
                                                                                        ธ.กรุงเทพ
                                                                                    </option>
                                                                                    <option value="ธ.กสิกรไทย">
                                                                                        ธ.กสิกรไทย
                                                                                    </option>
                                                                                    <option value="ธ.กรุงไทย">
                                                                                        ธ.กรุงไทย
                                                                                    </option>
                                                                                    <option value="ธ.ทหารไทย">
                                                                                        ธ.ทหารไทย
                                                                                    </option>
                                                                                    <option value="ธ.ไทยพาณิชย์">
                                                                                        ธ.ไทยพาณิชย์
                                                                                    </option>
                                                                                    <option value="ธ.กรุงศรีอยุธยา">
                                                                                        ธ.กรุงศรีอยุธยา
                                                                                    </option>
                                                                                    <option value="ธ.เกียรตินาคินภัทร">
                                                                                        ธ.เกียรตินาคินภัทร
                                                                                    </option>
                                                                                    <option value="ธ.ซีไอเอ็มบีไทย">
                                                                                        ธ.ซีไอเอ็มบีไทย
                                                                                    </option>
                                                                                    <option value="ธ.ทิสโก้">
                                                                                        ธ.ทิสโก้
                                                                                    </option>
                                                                                    <option value="ธ.ธนชาต">
                                                                                        ธ.ธนชาต
                                                                                    </option>
                                                                                    <option value="ธ.ยูโอบี">
                                                                                        ธ.ยูโอบี
                                                                                    </option>
                                                                                    <option value="ธ.ไทยเครดิตเพื่อรายย่อย">
                                                                                        ธ.ไทยเครดิตเพื่อรายย่อย
                                                                                    </option>
                                                                                    <option value="ธ.แลนด์แอนด์ เฮ้าส์">
                                                                                        ธ.แลนด์แอนด์ เฮ้าส์
                                                                                    </option>
                                                                                    <option value="ธ.ไอซีบีซี (ไทย)">
                                                                                        ธ.ไอซีบีซี (ไทย)
                                                                                    </option>
                                                                                    <option value="ธ.พัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย">
                                                                                        ธ.พัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย
                                                                                    </option>
                                                                                    <option value="ธ.เพื่อการเกษตรและสหกรณ์การเกษตร">
                                                                                        ธ.เพื่อการเกษตรและสหกรณ์การเกษตร
                                                                                    </option>
                                                                                    <option value="ธ.เพื่อการส่งออกและนำเข้าแห่งประเทศไทย">
                                                                                        ธ.เพื่อการส่งออกและนำเข้าแห่งประเทศไทย
                                                                                    </option>
                                                                                    <option value="ธ.ออมสิน">
                                                                                        ธ.ออมสิน
                                                                                    </option>
                                                                                    <option value="ธ.อาคารสงเคราะห์">
                                                                                        ธ.อาคารสงเคราะห์
                                                                                    </option>
                                                                                    <option value="ธ.อิสลามแห่งประเทศไทย">
                                                                                        ธ.อิสลามแห่งประเทศไทย
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Branch</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="branch" id="branch" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Acc. No.</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="acc_no" id="acc_no" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-headingom panel-clrom" role="tab" id="headingPaymentC">
                                                    <div class="panel-title">
                                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePaymentC" aria-expanded="false" aria-controls="collapsePaymentC">
                                                            &nbsp;Payment - Customer
                                                        </a>
                                                    </div>
                                                </div>
                                                <div id="collapsePaymentC" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingPaymentC">
                                                    <div class="panel-body panel-bodyom">
                                                        <div class="container">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Payment Method</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <select class="form-control form-controlom" name="payment_method" id="payment_method">
                                                                                    <option>Fund Transfer</option>
                                                                                    <option>Promtpay</option>
                                                                                    <option>Credit Card</option>
                                                                                    <option>Others</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Receive money</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="receive_money" id="receive_money" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: -25px;">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Not match Payment Remark</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="not_match_payment_remark" id="not_match_payment_remark" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm control-label col-form-label col-form-labelom">Payment Remark</label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input type="text" name="payment_remark" id="payment_remark" class="form-control form-controlom" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                    <!-- <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Bank</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <select class="form-control form-controlom">
                                                                                        <option></option>
                                                                                        <option>1</option>
                                                                                        <option>2</option>
                                                                                        <option>3</option>
                                                                                        <option>4</option>
                                                                                        <option>5</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Branch</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> -->
                                                                    <!-- <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Acc. No.</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Attach File</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="col-sm-9" style="font-size: 14px;">
                                                                                            <span class="form-control form-controlom" id="file-chosen" contenteditable="false" style="margin-left: -9px; background-color: #fff;"></span>
                                                                                        </div>
                                                                                        &nbsp;&nbsp;
                                                                                        <div class="input-group-prepend">
                                                                                            <input type="file" id="actual-btn" hidden="" />
                                                                                            <label
                                                                                                for="actual-btn"
                                                                                                class="btn btn-outline-secondary"
                                                                                                style="font-size: 11px; color: #2b2b2b; border-radius: 5px; font-family: PromptMedium; height: 25px; line-height: 13px;"
                                                                                            >
                                                                                                Browse
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-headingom panel-clrom" role="tab" id="headingVendorP">
                                                        <div class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseVendorP" aria-expanded="false" aria-controls="collapseVendorP">
                                                                &nbsp;Vendor - Payment
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="collapseVendorP" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingVendorP">
                                                        <div class="panel-body panel-bodyom">
                                                            <div class="container">
                                                                <div class="col-sm-12">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                                    Vender plant
                                                                                </label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="vender_plant" id="vender_plant" class="form-control form-controlom" />
                                                                                    <!-- <div class="input-group">
                                                                                        <input type="text" class="form-control form-controlom" placeholder="" name="vender_plant" id="vender_plant" />
                                                                                        <div class="input-group-append" style="height: 25px;">
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="10" height="10" /></i>
                                                                                            </span>
                                                                                            <span class="input-group-text" style="background-color: #ffffff; border: 0px;">
                                                                                                <i class="fa fa-minus"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div> -->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Payment Code</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="payment_code" id="payment_code" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">Vendor Bank</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <!-- <input type="text" name="vendor_bank" id="vendor_bank" class="form-control form-controlom" /> -->
                                                                                    <select class="form-control form-controlom" name="vendor_bank" id="vendor_bank">
                                                                                    <option value="--None--">
                                                                                        --None--
                                                                                    </option>
                                                                                    <option value="ธ.กรุงเทพ">
                                                                                        ธ.กรุงเทพ
                                                                                    </option>
                                                                                    <option value="ธ.กสิกรไทย">
                                                                                        ธ.กสิกรไทย
                                                                                    </option>
                                                                                    <option value="ธ.กรุงไทย">
                                                                                        ธ.กรุงไทย
                                                                                    </option>
                                                                                    <option value="ธ.ทหารไทย">
                                                                                        ธ.ทหารไทย
                                                                                    </option>
                                                                                    <option value="ธ.ไทยพาณิชย์">
                                                                                        ธ.ไทยพาณิชย์
                                                                                    </option>
                                                                                    <option value="ธ.กรุงศรีอยุธยา">
                                                                                        ธ.กรุงศรีอยุธยา
                                                                                    </option>
                                                                                    <option value="ธ.เกียรตินาคินภัทร">
                                                                                        ธ.เกียรตินาคินภัทร
                                                                                    </option>
                                                                                    <option value="ธ.ซีไอเอ็มบีไทย">
                                                                                        ธ.ซีไอเอ็มบีไทย
                                                                                    </option>
                                                                                    <option value="ธ.ทิสโก้">
                                                                                        ธ.ทิสโก้
                                                                                    </option>
                                                                                    <option value="ธ.ธนชาต">
                                                                                        ธ.ธนชาต
                                                                                    </option>
                                                                                    <option value="ธ.ยูโอบี">
                                                                                        ธ.ยูโอบี
                                                                                    </option>
                                                                                    <option value="ธ.ไทยเครดิตเพื่อรายย่อย">
                                                                                        ธ.ไทยเครดิตเพื่อรายย่อย
                                                                                    </option>
                                                                                    <option value="ธ.แลนด์แอนด์ เฮ้าส์">
                                                                                        ธ.แลนด์แอนด์ เฮ้าส์
                                                                                    </option>
                                                                                    <option value="ธ.ไอซีบีซี (ไทย)">
                                                                                        ธ.ไอซีบีซี (ไทย)
                                                                                    </option>
                                                                                    <option value="ธ.พัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย">
                                                                                        ธ.พัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย
                                                                                    </option>
                                                                                    <option value="ธ.เพื่อการเกษตรและสหกรณ์การเกษตร">
                                                                                        ธ.เพื่อการเกษตรและสหกรณ์การเกษตร
                                                                                    </option>
                                                                                    <option value="ธ.เพื่อการส่งออกและนำเข้าแห่งประเทศไทย">
                                                                                        ธ.เพื่อการส่งออกและนำเข้าแห่งประเทศไทย
                                                                                    </option>
                                                                                    <option value="ธ.ออมสิน">
                                                                                        ธ.ออมสิน
                                                                                    </option>
                                                                                    <option value="ธ.อาคารสงเคราะห์">
                                                                                        ธ.อาคารสงเคราะห์
                                                                                    </option>
                                                                                    <option value="ธ.อิสลามแห่งประเทศไทย">
                                                                                        ธ.อิสลามแห่งประเทศไทย
                                                                                    </option>
                                                                                </select>
                                                                                    <!-- <select class="form-control form-controlom" name="vendor_bank_order" id="vendor_bank_order">
                                                                                        <option></option>
                                                                                        <option>1</option>
                                                                                        <option>2</option>
                                                                                    </select> -->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Vendor Bank Account</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="vendor_bank_account" id="vendor_bank_account" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top: -25px;">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Credit term</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="credit_term" id="credit_term" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm control-label col-form-label col-form-labelom">Vendor Register Address</label>
                                                                                <div class="col-sm-9 col-sm-9om">
                                                                                    <input type="text" name="vendor_register_address" id="vendor_register_address" class="form-control form-controlom" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-headingom panel-clrom" role="tab" id="headingVendorT">
                                                        <div class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseVendorT" aria-expanded="false" aria-controls="collapseFour">
                                                                &nbsp;Vendor - Terms & Conditions
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="collapseVendorT" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingVendorT">
                                                        <div class="panel-body panel-bodyom">
                                                            <div class="container">
                                                                <div class="col-sm-12">
                                                                    <div class="row m-t-5 m-b-5">
                                                                        <label class="control-label col-form-label col-form-labelom">Description Vendor</label>
                                                                        <div class="col-sm">
                                                                            <textarea class="form-control" rows="3" name="description_vendor" id="description_vendor">* อาจมีการเปลียนแปลงแผนวันที่ใช้งาน โดยเจ้าหน้าที่ผู้ได้รับอนุญาตจากมิกซ์ อีซี่
                                                                            </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-headingom panel-clrom" role="tab" id="headingFour">
                                                        <div class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                                &nbsp;<?php echo genLabel('LBL_DESCRIOTION_INFORMATION') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="collapseFour" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingFour">
                                                        <div class="panel-body panel-bodyom">
                                                            <div class="container">
                                                                <div class="col-sm-12">
                                                                    <div class="row m-t-5 m-b-5">
                                                                        <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="margin-left: 10px;">
                                                                            Remark
                                                                        </label>
                                                                        <div class="col-sm" style="background-color: #ffffff; margin-left: 10px;">
                                                                            <textarea class="form-control" rows="3" name="description" id="description_order"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default">
                                                    <div class="panel-headingom panel-clrom" role="tab" id="headingFive">
                                                        <div class="panel-title" style="padding: 15px;">
                                                            <div class="row d-flex no-block idhead">
                                                                <a
                                                                class="collapsed"
                                                                role="button"
                                                                data-toggle="collapse"
                                                                data-parent="#accordion"
                                                                href="#collapseFive"
                                                                aria-expanded="false"
                                                                aria-controls="collapseFive"
                                                                style="margin-left: 10px;"
                                                                >
                                                                &nbsp;<?php echo genLabel('LBL_ITEM_DETAILS') ?>
                                                            </a>
                                                                <!-- <div class="ml-auto item">
                                                                    <div class="row" style="padding-right: 10px;">
                                                                        <div class="form-group row" style="margin-bottom: 0;">
                                                                            <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="padding-bottom: 0px; margin-top: 0px; padding-right: 0px;">
                                                                                Price Type
                                                                            </label>
                                                                            <div class="col-sm" style="padding-right: 30px;">
                                                                                <select class="form-control form-controlom" style="min-height: 0px; height: 30px; font-size: 11px;" name="pricetype" id="pricetype">
                                                                                    <option selected value="Exclude Vat">Exclude Vat</option>
                                                                                    <option value="Include Vat">Include Vat</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" style="margin-bottom: 0;">
                                                                            <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="padding-bottom: 0px; margin-top: 0px; padding-right: 0px;">
                                                                                Currency
                                                                            </label>
                                                                            <div class="col-sm" style="padding-right: 30px;">
                                                                                <select class="form-control form-controlom" style="min-height: 0px; height: 30px; font-size: 11px;" name="inventory_currency" id="inventory_currency">
                                                                                    <option selected="" value="1">Thailand,Bant (฿) </option>
                                                                                    <option value="2">USA, Dollars ($)</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" style="margin-bottom: 0;">
                                                                            <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="padding-bottom: 0px; margin-top: 0px; padding-right: 0px;">
                                                                                Tax Mode
                                                                            </label>
                                                                            <div class="col-sm">
                                                                                <select id="taxtype" name="taxtype" class="form-control form-controlom" style="min-height: 0px; height: 30px; font-size: 11px;">
                                                                                    <option value="group" selected="">Group</option>
                                                                                    <option value="individual">individual</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="collapseFive" class="panel-collapse panel-collapseom collapse" role="tabpanel" aria-labelledby="headingFive">
                                                        <div class="panel-body panel-bodyom">
                                                            <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                                                                <div class="table-responsive text-center">
                                                                    <table class="table tableom" style="margin-bottom: 0px;">
                                                                        <thead style="font-size: 12px; color: #2b2b2b; font-family: PromptMedium;">
                                                                            <tr>
                                                                                <th style="width: 30%;">รายการสินค้า</th>
                                                                                <th style="width: 4%;">Km</th>
                                                                                <th style="width: 4%;">Zone</th>
                                                                                <th style="width: 6%;">ขนาดรถ</th>
                                                                                <!-- <th>Plant ID</th> -->
                                                                                <th style="width: 4%;">หน่วย</th>
                                                                                <th>จำนวน</th>
                                                                                <th style="width: 7%;">ราคา/หน่วย</th>
                                                                                <th style="width: 9%;">จำนวนเงิน</th>
                                                                                <th style="width: 5%;">Min</th>
                                                                                <th style="width: 5%;">DLV_C</th>
                                                                                <th>DLV_C+VAT</th>
                                                                                <th>DLV_P+VAT</th>
                                                                                <th style="width: 6%;">LP</th>
                                                                                <th style="width: 4%">ส่วนลด</th>
                                                                                <th style="width: 4%;">C_Cost</th>
                                                                                <th style="width: 7%;">ราคาหลังหักส่วนลด (ก่อน VAT)</th>
                                                                                <th style="width: 9%;">จำนวนเงิน<br>(ซื้อ)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="border_bottom">
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="productName1"
                                                                                    id="productName1"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; background-color: #fff;"
                                                                                    readonly/>
                                                                                    <input type="hidden" name="ProductId" id="ProductId">
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="km"
                                                                                    id="km"
                                                                                    value=""
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; background-color: #ededed; text-align: right;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    name="zone" id="zone"
                                                                                    type="text"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; background-color: #ededed;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="carsize"
                                                                                    id="carsize"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; background-color: #ededed;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <!-- <td>
                                                                                    <input
                                                                                        class="form-control form-controlom"
                                                                                        type="text"
                                                                                        value="1000990"
                                                                                        style="font-size: 1px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; width: 60px; background-color: #ededed;"
                                                                                    />
                                                                                </td> -->
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    value="ลบ.ม"
                                                                                    name="unit1"
                                                                                    id="unit1"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input class="form-control form-controlom" type="text" name="number1" id="number1" value="0" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;" onblur="calculator_order(); set_calculator();"  />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="priceperunit1"
                                                                                    id="priceperunit1"
                                                                                    value=""
                                                                                    onblur="calculator_order(); set_calculator();"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;text-align: right;"
                                                                                    />
                                                                                    <input id="c_price_vat" type="hidden" name="c_price_vat">
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="amount1"
                                                                                    id="amount1"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="min"
                                                                                    id="min"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-right: 5px; text-align: right;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="dlv_c"
                                                                                    id="dlv_c"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="dlv_cvat"
                                                                                    id="dlv_cvat"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="dlv_pvat"
                                                                                    id="dlv_pvat"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="lp"
                                                                                    id="lp"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="discount"
                                                                                    id="discount"
                                                                                    onblur="calculator_order();set_discount(); set_calculator();"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="c_cost"
                                                                                    id="c_cost"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="afterdiscount1"
                                                                                    id="afterdiscount1"
                                                                                    onblur="set_afterdiscount1(); set_calculator();"
                                                                                    value="0"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td style="background-color: #ededed; text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium; border-bottom: 1px solid #ededed;">
                                                                                    <span name="purchaseamount1show" id="purchaseamount1show" style="text-align: right;">
                                                                                    </span>
                                                                                    <input type="hidden" name="purchaseamount1" id="purchaseamount1" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border_bottom">
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="productName2"
                                                                                    id="productName2"
                                                                                    value="ค่าขนส่ง (ไม่เต็มเที่ยว)"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; background-color: #fff;"
                                                                                    readonly/>
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="unit2"
                                                                                    id="unit2"
                                                                                    value="รายการ"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="number2"
                                                                                    id="number2"
                                                                                    onblur="calculator_order()"
                                                                                    value="0"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="priceperunit2"
                                                                                    id="priceperunit2"
                                                                                    value="0"
                                                                                    onblur="calculator_order()"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="amount2"
                                                                                    id="amount2"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="afterdiscount2"
                                                                                    id="afterdiscount2"
                                                                                    value="0"
                                                                                    onblur="set_afterdiscount2(); set_calculator();"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td style="background-color: #ededed; text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium; border-bottom: 1px solid #ededed;">
                                                                                    <span name="purchaseamount2show" id="purchaseamount2show" style="text-align: right;"></span>
                                                                                    <input type="hidden" name="purchaseamount2" id="purchaseamount2" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border_bottom">
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="productName3"
                                                                                    id="productName3"
                                                                                    value="ค่าบริการ ปั๊มลาก (ปริมาณคอนกรีตผ่านปั๊มไม่เกิน 50 ลบ.ม"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; background-color: #fff;"
                                                                                    readonly/>
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="unit3"
                                                                                    id="unit3"
                                                                                    value="รายการ"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    value="0"
                                                                                    name="number3"
                                                                                    id="number3"
                                                                                    onblur="calculator_order()"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="priceperunit3"
                                                                                    id="priceperunit3"
                                                                                    value="0"
                                                                                    onblur="calculator_order()"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="amount3"
                                                                                    id="amount3"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    readonly  
                                                                                    />
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="afterdiscount3"
                                                                                    id="afterdiscount3"
                                                                                    value="0"
                                                                                    onblur=" set_afterdiscount3(); set_calculator();"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td style="background-color: #ededed; text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium; border-bottom: 1px solid #ededed;">
                                                                                    <span name="purchaseamount3show" id="purchaseamount3show" style="text-align: right;"></span>
                                                                                    <input type="hidden" name="purchaseamount3" id="purchaseamount3" />
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="productName4"
                                                                                    id="productName4"
                                                                                    value="ค่าบริการ เก็บตัวอย่างก้อนปูน (Cube)"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; background-color: #fff;"
                                                                                    readonly/>
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="unit4"
                                                                                    id="unit4"
                                                                                    value="รายการ"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="number4"
                                                                                    id="number4"
                                                                                    value="0"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    onblur="calculator_order()"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="priceperunit4"
                                                                                    id="priceperunit4"
                                                                                    value="0"
                                                                                    onblur="calculator_order()"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="amount4"
                                                                                    id="amount4"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    readonly
                                                                                    />
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td>
                                                                                    <input
                                                                                    class="form-control form-controlom"
                                                                                    type="text"
                                                                                    name="afterdiscount4"
                                                                                    id="afterdiscount4"
                                                                                    value="0"
                                                                                    onblur="set_afterdiscount4(); set_calculator();"
                                                                                    style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; padding-left: 5px; text-align: right;"
                                                                                    />
                                                                                </td>
                                                                                <td style="background-color: #ededed; text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">
                                                                                    <span name="purchaseamount4show" id="purchaseamount4show" style="text-align: right;"></span>
                                                                                    <input type="hidden" name="purchaseamount4" id="purchaseamount4" />
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr class="border_top">
                                                                                <td colspan="7" style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">รวม</td>
                                                                                <td style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">
                                                                                    <span id="subTotal1show" name="subTotal1show"></span>
                                                                                    <input type="hidden" name="subTotal1" id="subTotal1">
                                                                                </td>
                                                                                <td colspan="8" style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">รวม</td>
                                                                                <td style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">
                                                                                    <span name="subTotal2show" id="subTotal2show"></span>
                                                                                    <input type="hidden" name="subTotal2" id="subTotal2">
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border_top">
                                                                                <td colspan="7" style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">ภาษีมูลค่าเพิ่ม 7%</td>
                                                                                <td style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">
                                                                                    <span name="Vat1show" id="Vat1show"></span>
                                                                                    <input type="hidden" name="Vat1" id="Vat1">
                                                                                </td>
                                                                                <td colspan="8" style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">ภาษีมูลค่าเพิ่ม 7%</td>
                                                                                <td style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">
                                                                                    <span name="Vat2show" id="Vat2show"></span>
                                                                                    <input type="hidden" name="Vat2" id="Vat2">
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="border_top" style="background-color: #f7f7f7;">
                                                                                <td colspan="7" style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;"><b>รวมทั้งสิ้น</b></td>
                                                                                <td style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">
                                                                                    <span name="Total1show" id="Total1show"></span>
                                                                                    <input type="hidden" name="Total1" id="Total1">
                                                                                </td>
                                                                                <td colspan="8" style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;"><b>รวมทั้งสิ้น</b></td>
                                                                                <td style="text-align: right; font-size: 14px; color: #2b2b2b; font-family: PromptMedium;">
                                                                                    <span name="Total2show" id="Total2show"></span>
                                                                                    <input type="hidden" name="Total2" id="Total2">
                                                                                </td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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

<!-- modal popup km -->
<div id="modalPopupKm" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2b2b2b; font-weight: 700;">
                <b>องค์ความรู้</b>
            </span>
            <span class="close" id="closepopupkm"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
            <br />
        </div>
        <div class="modal-body">
            <div class="col-12">
                <div class="col-sm-12">
                    <!-- <div class="row">
                        <div class="col-sm-2" style="margin: auto;">
                            <p style="color: #2b2b2b; font-family: PromptMedium; font-size: 12px; font-weight: 600;">เงื่อนไขค้นหา</p>
                        </div>
                        <div class="col-sm" style="border: 1px solid #ededed; border-radius: 5px;">
                            <form id="form_km">
                                <div class="row" style="padding-top: 20px; padding-left: 10px;">
                                    <div class="col-sm-5">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">ประเภท:</label>
                                            <div class="col-sm">
                                                <select class="form-control" style="font-size: 14px; padding: 1px; font-family: PromptMedium; min-height: 25px;">
                                                    <option value="1">ประเภทองค์ความรู้</option>
                                                </select>
                                                <select class="select2 form-control custom-select" style="width: 100%; height: 36px; font-family: PromptMedium; font-size: 12px;">
                                                    <option>ประเภทองค์ความรู้</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium;">คำค้นหา:</label>
                                            <div class="col-sm">
                                                <input class="form-control" type="text" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-success d-none d-lg-block btncallin" style="float: left;">
                                            <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15" /></i>
                                            ค้นหา
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> -->
                    <div class="row" style="float: right; margin-top: 10px;">
                        <button type="button" class="btn btn-success d-none d-lg-block btncallin" style="font-size: 12px; font-weight: 500; font-family: PromptMedium;">
                            VOTE
                        </button>
                        &nbsp;&nbsp;
                        <button type="button" onclick="$.emailKm();" class="btn btn-success d-none d-lg-block btncallin">
                            <i><img src="<?php echo site_assets_url('images/icons/sendemailb.png'); ?>" width="18" height="15" /></i>
                        </button>
                    </div>
                    <br />
                    <br />
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12">
                            <div id="Knowledge1" class="tabcontent">
                                <div class="col-12" style="height: 100%;">
                                    <div class="row">
                                        <div class="col-sm-12" style="font-size: 16px; padding: 10px; background-color: #ededed; width: 100%; border-radius: 5px; color: #2b2b2b; font-family: PromptMedium; font-weight: 500; margin-left: 20px;">
                                            รายการความรู้
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 15px; margin-left: 30px;">
                                        
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item nan-km">
                                                <a class="nav-link active" data-toggle="tab" href="#km1">KM</a>
                                            </li>
                                            <li class="nav-item nan-km">
                                                <a class="nav-link" data-toggle="tab" href="#comment">Comment</a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <div style="box-shadow: 0px 3px 8px #e5e5e5; margin-left: 10px; border-radius: 5px; overflow: visible; height: 502px;">
                                        <div class="tab-content" style="padding: 20px;">
                                            <div id="km1" class="tab-pane active">
                                                <br />
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <i class="fa fa-book" aria-hidden="true"></i>
                                                        &nbsp;&nbsp;
                                                        <span style="color: #2b2b2b; font-family: PromptMedium; font-size: 16px; font-weight: 500;" id="knowledgebase_name">ผู้ประกอบการ จะได้รับสิทธิอะไรบ้าง หากเป็นสมาชิกหน่วยงานพันธมิตร</span>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                        <span style="color: #2b2b2b; font-size: 16px; font-family: PromptMedium; font-weight: 400; line-height: 1.5;" id="know_detail">
                                                            นอกจากการได้รับแจ้งข่าวสารในวงการธุรกิจที่ทันเหตุการณ์แล้ว สมาชิกยังได้รับสิทธิ์เข้าร่วมโครงการต่างๆของภาครัฐผ่านหน่วยงานพันธมิตร เช่น ฝึกอบรม ประชุม สัมมนา
                                                            โอกาสทางการตลาดการจับคู่ธุรกิจ การออกแบบบูธสินค้า รับคำปรึกษาแนะนำด้านการค้า การลงทุน ปรับปรุงธุรกิจและด้านสินเชื่อ หากส่งผ่านหน่วยงานพันธมิตร จะได้รับการพิจารณาพิเศษ
                                                            เนื่องจากผ่านการคัดกรอง จากหน่วยงานพันธมิตร อ่านต่อที่:
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="comment" class="container tab-pane fade">
                                                <br />
                                                <h3>comment</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="tab">
                            <div style="background-color: #ededed; padding: 10px; border-radius: 5px 5px 0px 0px; font-size: 16px; font-family: PromptMedium; font-weight: 500; color: #2b2b2b;">รายการความรู้</div>
                            <button class="tablinks" onclick="openCity(event, 'Knowledge1')">
                                <i class="fa fa-book" aria-hidden="true"></i>
                                <span style="color: #2b2b2b; font-family: PromptMedium; font-size: 12px; font-weight: 400;">ผู้ประกอบการ จะได้รับสิทธิอะไรบ้าง หากเป็นสมาชิกหน่วยงานพันธมิตร</span>
                            </button>
                            <button class="tablinks" onclick="openCity(event, 'Knowledge2')">
                                <i class="fa fa-book" aria-hidden="true"></i>
                                <span style="color: #2b2b2b; font-family: PromptMedium; font-size: 12px; font-weight: 400;">ผู้ประกอบการ จะได้รับสิทธิอะไรบ้าง หากเป็นสมาชิกหน่วยงานพันธมิตร</span>
                            </button>
                            <button class="tablinks" onclick="openCity(event, 'Paris')">Paris</button>
                            <button class="tablinks" onclick="openCity(event, 'Tokyo')">Tokyo</button>
                        </div> -->

                        <!-- <div id="Knowledge1" class="tabcontent">
                            <div class="col-12" style="border-left: 1px solid #ededed; height: 100%;">
                                <div class="row">
                                    <div class="col-sm-12" style="font-size: 16px; padding: 10px; background-color: #ededed; width: 100%; border-radius: 5px; color: #2b2b2b; font-family: PromptMedium; font-weight: 500; margin-left: 20px;">
                                        รายการความรู้
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 15px; margin-left: 30px;">
                                    
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item nan-km">
                                            <a class="nav-link active" data-toggle="tab" href="#km1">KM</a>
                                        </li>
                                        <li class="nav-item nan-km">
                                            <a class="nav-link" data-toggle="tab" href="#comment">Comment</a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div style="box-shadow: 0px 3px 8px #e5e5e5; margin-left: 10px; border-radius: 5px; overflow: visible; height: 502px;">
                                    <div class="tab-content" style="padding: 20px;">
                                        <div id="km1" class="container tab-pane active">
                                            <br />
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <i class="fa fa-book" aria-hidden="true"></i>
                                                    &nbsp;&nbsp;
                                                    <span style="color: #2b2b2b; font-family: PromptMedium; font-size: 16px; font-weight: 500;">ผู้ประกอบการ จะได้รับสิทธิอะไรบ้าง หากเป็นสมาชิกหน่วยงานพันธมิตร</span>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <span style="color: #2b2b2b; font-size: 16px; font-family: PromptMedium; font-weight: 400; line-height: 1.5;">
                                                        นอกจากการได้รับแจ้งข่าวสารในวงการธุรกิจที่ทันเหตุการณ์แล้ว สมาชิกยังได้รับสิทธิ์เข้าร่วมโครงการต่างๆของภาครัฐผ่านหน่วยงานพันธมิตร เช่น ฝึกอบรม ประชุม สัมมนา
                                                        โอกาสทางการตลาดการจับคู่ธุรกิจ การออกแบบบูธสินค้า รับคำปรึกษาแนะนำด้านการค้า การลงทุน ปรับปรุงธุรกิจและด้านสินเชื่อ หากส่งผ่านหน่วยงานพันธมิตร จะได้รับการพิจารณาพิเศษ
                                                        เนื่องจากผ่านการคัดกรอง จากหน่วยงานพันธมิตร อ่านต่อที่:
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="comment" class="container tab-pane fade">
                                            <br />
                                            <h3>comment</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div id="Knowledge2" class="tabcontent" style="display: none;">
                            <div class="col-12" style="border-left: 1px solid #ededed; height: 100%;">
                                <div class="row">
                                    <div class="col-sm-12" style="font-size: 16px; padding: 10px; background-color: #ededed; width: 100%; border-radius: 5px; color: #2b2b2b; font-family: PromptMedium; font-weight: 500; margin-left: 20px;">
                                        รายการความรู้
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 15px; margin-left: 30px;">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item nan-km">
                                            <a class="nav-link active" data-toggle="tab" href="#km2">KM</a>
                                        </li>
                                        <li class="nav-item nan-km">
                                            <a class="nav-link" data-toggle="tab" href="#comment">Comment</a>
                                        </li>
                                    </ul>
                                </div>
                                <div style="box-shadow: 0px 3px 8px #e5e5e5; margin-left: 10px; border-radius: 5px; overflow: visible; height: 502px;">
                                    <div class="tab-content" style="padding: 20px;">
                                        <div id="km2" class="container tab-pane active">
                                            <br />
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <i class="fa fa-book" aria-hidden="true"></i>
                                                    &nbsp;&nbsp;
                                                    <span style="color: #2b2b2b; font-family: PromptMedium; font-size: 16px; font-weight: 500;">ผู้ประกอบการ จะได้รับสิทธิอะไรบ้าง หากเป็นสมาชิกหน่วยงานพันธมิตร</span>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <span style="color: #2b2b2b; font-size: 16px; font-family: PromptMedium; font-weight: 400; line-height: 1.5;">5555555: </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="comment" class="container tab-pane fade">
                                            <br />
                                            <h3>comment</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
 -->
                        <!-- <div id="Paris" class="tabcontent">
                          <h3>Paris</h3>
                          <p>Paris is the capital of France.</p>
                      </div>

                      <div id="Tokyo" class="tabcontent">
                          <h3>Tokyo</h3>
                          <p>Tokyo is the capital of Japan.</p>
                      </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalEmailKm" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2b2b2b; font-weight: 500; margin: 0 auto;">
                Send Email
            </span>
            <span id="closeemailkm" style="cursor: pointer;"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
            <br />
        </div>
        <div class="modal-body">
            <div class="col-sm-12">
                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="color: #2b2b2b; font-size: 12px; margin-top: -4px; font-weight: 400;">To</label>
                    <div class="col-sm">
                        <select class="select2 form-control custom-select" style="width: 100%; height: 36px;">
                            <option>Email Address</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="margin-top: -15px;">
                    <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="color: #2b2b2b; font-size: 12px; margin-top: -4px; font-weight: 400;">CC</label>
                    <div class="col-sm">
                        <input class="form-control" type="text" placeholder="CC Email" />
                    </div>
                </div>
                <div class="form-group row" style="margin-top: -15px;">
                    <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="color: #2b2b2b; margin-top: -4px; font-size: 12px; font-weight: 400;">Subject</label>
                    <div class="col-sm">
                        <input class="form-control" type="text" />
                    </div>
                </div>
                <div class="form-group row" style="margin-top: -15px;">
                    <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2b2b2b;"> Attach File </label>
                    <div class="col-sm-10">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <!-- actual upload which is hidden -->
                                <input type="file" id="emailfile-btn" hidden="" />
                                <!-- our custom upload button -->
                                <label for="emailfile-btn" class="btn btn-outline-secondary" style="font-size: 11px; color: #2b2b2b; border-radius: 5px; font-family: PromptMedium; height: 25px; line-height: 13px;">Choose Files</label>
                            </div>
                            <div class="col-sm" style="margin-right: 14px;">
                                <span
                                    class="form-control"
                                    id="fileemail-chosen"
                                    contenteditable="false"
                                    style="font-size: 11px; margin-left: 0.3rem; position: absolute; min-height: 25px; border-radius: 0.25rem; height: 25px; line-height: 13px; font-family: PromptMedium; font-weight: 400;"
                                >
                                    choose files to upload
                                </span>
                                <!-- <label class="custom-file-label form-control" id="file-chosen">Choose file</label> -->
                            </div>
                        </div>
                    </div>
                </div>
                <textarea name="detail" id="detail"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success d-none d-lg-block btncallin">
                <i><img src="<?php echo site_assets_url('images/icons/sendemailb.png'); ?>" width="17" height="14" /></i>
                ส่งเมล
            </button>
        </div>
    </div>
</div>

<div id="modalPopupFaq" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-family: PromptMedium; color: #2b2b2b; font-size: 16px; font-weight: 700;">FAQ</span>
            <span class="close" id="closepopupfaq"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
            <br />
        </div>
        <div class="modal-body">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-12" style="background-color: #ededed; padding: 10px; border-radius: 5px; color: #2b2b2b; font-size: 16px; font-family: PromptMedium; font-weight: 500;">
                        <span id="faqno">FAQ000004</span> : <span id="faqname">ไม่ได้รับใบแจ้งหนี้/ใบเสร็จรับเงิน หรือได้รับล่าช้าเกินวันครบกำหนดไปแล้ว...?</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <ul class="nav nav-tabs" role="tablist" style="margin-left: 20px;">
                        <li class="nav-item nan-km">
                            <a class="nav-link active" data-toggle="tab" href="#faq1">FAQ</a>
                        </li>
                    </ul>
                </div>
                <div class="row" style="box-shadow: 0px 3px 8px #ededed; border-radius: 5px;">
                    <div class="tab-content" style="padding: 20px;">
                        <div id="faq1" class="tab-pane active">
                            <br />
                            <div class="col-sm-12">
                                <hr />
                                <div class="row">
                                    <span id="faqdetail" style="font-family: PromptMedium; color: #2b2b2b; font-size: 16px; font-weight: 400;">
                                        1. ตรวจสอบที่อยู่ของลูกค้าว่าถูกต้องกับข้อมูลที่ให้ไว้กับธนาคารหรือไม่ 2. สอบถามว่าได้รับบัตรบาร์โค้ดหรือยัง ถ้ายังไม่ได้รับให้ติดต่อเจ้าหน้าที่ฝ่ายบัญชีสินเชื่อ หรือเจ้าหน้าที่ที่ดูแลลูกหนี้ 3.
                                        แจ้งภาระหนี้ค่างวด โดยดูจากใบแจ้งหนี้ในระบบเพื่อให้ลูกหนี้ไปชำระโดยใช้ใบแจ้งหนี้ของเดือนก่อนได้ 4. ถ้าลูกค้าสะดวกไปที่สาขาได้ ให้ไปติดต่อเจ้าหน้าที่เพื่อขอใบแจ้งหนี้ซึ่งสามารถพิมพ์ใบแจ้งหนี้ได้เอง
                                        หรือไปชำระหนี้ที่สาขาได้เลย 5.ติดต่อเจ้าหน้าที่ : อ่านต่อที่
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalPopuphistorycase" class="modal" style="overflow: hidden;">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-family: PromptMedium; color: #2b2b2b; font-size: 16px; font-weight: 700;">History Case</span>
            <span class="close" id="closepopuphistorycase"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15" /></span>
            <br />
        </div>
        <div class="modal-body">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <span style="background-color: #ededed; width: 100%; padding: 10px; border-radius: 5px 5px 0px 0px; font-family: PromptMedium; color: #2b2b2b; font-size: 16px; font-weight: 500;">Case Information</span>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="background-color: #f7f7f7; padding-left: 50px;">
                                <div class="row m-t-5">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; font-family: PromptMedium; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Case No
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="caseno" id="caseno" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Task name
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="" class="form-control" name="taskname" id="taskname" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Status
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="" class="form-control" name="statushistorycase" id="statushistorycase" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Priority
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="priority" id="priority" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Description
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" rows="2" name="description_case" id="description_case" style="background-color: #fff;" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Note
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" rows="2" name="notes_case" id="notes_case" style="background-color: #fff;" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Contact Channel
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="" class="form-control" name="contactchannel_case" id="contactchannel_case" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Response
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="response_case" id="response_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Handled by
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="handledby_case" id="handledby_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                       <!--  <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Created By
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <!-- <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Created Time
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="createdtime_case" id="createdtime_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Modified By
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="modifiedby_case" id="modifiedby_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-family: PromptMedium; font-weight: 400; text-align: right;">
                                                Modified Time
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="modifiedtime_case" id="modifiedtime_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-12">
                        <div class="row">
                            <span style="background-color: #ededed; width: 100%; padding: 10px; border-radius: 5px 5px 0px 0px; font-family: PromptMedium; color: #2b2b2b; font-size: 16px; font-weight: 500;">Customer Information</span>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="background-color: #f7f7f7; padding-left: 50px;">
                                <div class="row m-t-5">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; font-family: PromptMedium; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Contact Name
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="contact_name_case" id="contact_name_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; font-family: PromptMedium; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Account Name
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="account_name_case" id="account_name_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; font-family: PromptMedium; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Tel.
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tel_case" id="tel_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; font-family: PromptMedium; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Email
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="email_case" id="email_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; font-family: PromptMedium; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Line ID
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="line_id_case" id="line_id_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; font-family: PromptMedium; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Facebook
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="facebook_case" id="facebook_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-12">
                        <div class="row">
                            <span style="background-color: #ededed; width: 100%; padding: 10px; border-radius: 5px 5px 0px 0px; color: #2b2b2b; font-size: 16px; font-family: PromptMedium; font-weight: 500;">Date Information</span>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="background-color: #f7f7f7; padding-left: 50px;">
                                <div class="row m-t-5">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Case Date
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="case_date_case" id="case_date_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Case time
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="case_time_case" id="case_time_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Date of execution
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="date_of_execution_case" id="date_of_execution_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Process time
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="process_time_case" id="process_time_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Date Completed
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="date_completed_case" id="date_completed_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Time Completed
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="time_completed_case" id="time_completed_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -25px;">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Date Cancelled
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="date_cancelled_case" id="date_cancelled_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Time Cancelled
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="time_cancelled_case" id="time_cancelled_case" class="form-control" style="background-color: #fff;" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-12">
                        <div class="row">
                            <span style="background-color: #ededed; width: 100%; padding: 10px; border-radius: 5px 5px 0px 0px; color: #2b2b2b; font-family: PromptMedium; font-weight: 500; font-size: 16px;">Attach Information</span>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="background-color: #f7f7f7; padding-left: 50px;">
                                <div class="row m-t-5">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2b2b2b; font-weight: 400; text-align: right;">
                                                Attach file
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="file_upload_name" id="file_upload_name" class="form-control" style="background-color: #fff;" readonly />
                                                <input type="hidden" name="file_upload_path" id="file_upload_path">
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

<style type="text/css">
    @font-face {
        font-family: PromptMedium;
        src: url(assets/fonts/Prompt-Medium.ttf);
    }

    input{
        font-size: 11px !important;
    }
    select{
        font-size: 11px !important;
    }
    textarea{
        font-size: 11px !important;
    }

    .row1 {
        margin-top: 15px;
    }

    .page-wrapper {
        /*background: #DBDBDB;*/
        background-image: linear-gradient(180deg, #ffffff, #a9a9a9);
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
        color: #ffffff;
        background-color: #e97126;
        border-color: #e97126 #e97126 #e97126;
        font-family: PromptMedium;
        font-size: 16px;
        font-weight: 500;
    }

    .one {
        border-bottom: 0px solid #e97125;
    }

    .nav-tabs .one {
        border-bottom: 0px solid #e97125;
    }

    .nav-tabs {
        margin-top: -3px;
    }

    .nav-tabs .nav-link {
        color: #000;
        border-color: #e3e1de;
        font-size: 16px;
        font-family: PromptMedium;
        font-weight: 500;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        border-bottom: 0px;
        border: 1px solid transparent;
        text-decoration: none;
    }

    .btnsave {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
        /*box-shadow: 1px 0px 10px #E5E5E5;*/
        float: right;
    }

    .btncallin {
        background-color: #ffffff;
        color: #2b2b2b;
        border-color: #ffffff;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        float: right;
        font-family: PromptMedium;
        font-size: 11px;
        font-weight: 500;
    }

    .btncallin:hover {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
    }

    .btnsave {
        background-color: #ffffff;
        color: #e97126;
        /*border-color: #ffffff;*/
        box-shadow: 1px 0px 10px #e5e5e5;
        /*font-weight: bold;*/
        font-family: PromptMedium;
        font-size: 11px;
    }

    .btnsave:hover {
        color: #e97126;
        border-color: #ffffff;
    }

    .btnmoreinfo {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
        box-shadow: 1px 0px 10px #e5e5e5;
    }

    .btnclear {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
        box-shadow: 1px 0px 10px #e5e5e5;
    }

    .btnadvancesearch {
        background-color: #ffffff;
        color: #000000;
        border-color: #ffffff;
        box-shadow: 1px 0px 10px #e5e5e5;
    }

    /*.btn-success:hover {
        color: #ffffff;
        background-color: #078BEA;
        border-color: #078BEA;
    }*/

    .save:hover {
        background-color: #018ffb;
        color: #fff;
    }

    .btn-success:not(:disabled):not(.disabled).active:focus, .btn-success:not(:disabled):not(.disabled):active:focus, .show>.btn-success.dropdown-toggle:focus {
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
    }

    .btn-success:not(:disabled):not(.disabled).active, .btn-success:not(:disabled):not(.disabled):active, .show>.btn-success.dropdown-toggle {
        background-color: #fff;
        color: #e97126;
        border-color: #fff;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
    }

    .page-titles {
        margin: -20px -20px 15px -20px;
        padding: 0px;
        background: #ededed;
        padding-top: 0px;
        border-bottom: 3px solid #e97126;
    }

    ul.list-inline li {
        background: #ffffff;
        padding: 5px;
        border-radius: 4px;
        white-space: nowrap;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
    }

    ul.list-inline li a {
        color: #000000;
        font-family: PromptMedium;
    }

    /*ul.list-inline li:hover {
        color: #ffffff;
        background-color: #078BEA;
        border-color: #078BEA;
        }*/

    /*ul.list-inline li a:hover{
        color: #ffffff;
        }*/

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
        margin-bottom: 20px;
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

    table td.centered {
        text-align: center;
    }

    .form-control {
        font-family: PromptMedium;
        height: 20px;
        min-height: 25px;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #ededed;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        background-color: #ededed;
        height: 25px;
    }

    .select2-container .select2-selection--single {
        height: 25px;
        border: 1px solid #e9ecef;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #2b2b2b transparent transparent transparent;
    }

    .input-group-text {
        background-color: #ededed;
    }

    .form-control::placeholder {
        color: #a9a9a9;
        opacity: 1;
        font-family: PromptMedium;
    }

    .col-form-label {
        font-family: PromptMedium;
    }

    .select2-results__option {
        font-family: PromptMedium;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 25px;
        font-family: PromptMedium;
        color: #2b2b2b;
        font-size: 11px;
    }

    .h4,
    h4 {
        font-family: PromptMedium;
    }

    .demo {
        padding-top: 10px;
        padding-bottom: 60px;
    }

    .panel-clr {
        background-color: #ededed;
        border-radius: 5px 5px 0px 0px;
    }

    .panel-clr.on {
        background-color: #fecfb1;
        border-radius: 5px 5px 0px 0px;
    }

    .panel-heading {
        padding: 0;
        border: 0;
    }
    .panel-title > a,
    .panel-title > a:active {
        display: block;
        padding: 15px;
        color: #2b2b2b;
        font-size: 12px;
        font-family: PromptMedium;
        font-weight: bold;
        text-transform: none;
        letter-spacing: 1px;
        word-spacing: 3px;
        text-decoration: none;
    }
    .panel-heading a:before {
        font-family: "Glyphicons Halflings";
        /*content: "\e114";*/
        content: "▲";
        float: right;
        transition: all 0.5s;
    }
    .panel-heading.active a:before {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-body {
        height: 100%;
        background-color: #ffffff;
        border: 1px solid #ededed;
        /*overflow: auto;*/
        border-radius: 0px 0px 5px 5px;
    }

    .panel-default {
        padding-bottom: 5px;
    }

    .input-daterange {
        font-family: PromptMedium;
    }

    .formbill {
        background-color: #f7f7f7;
    }

    .formsearching {
        min-height: 5px;
        height: 30px;
        padding: 1px;
    }

    .colsm12searching {
        padding-left: 5px;
        padding-left: 5px;
        padding-bottom: 5px;
    }

    .colsm6searching {
        border: 1px solid #ededed;
        padding: 0px;
    }

    .btnsavesearching {
        color: #e97126;
        font-family: PromptMedium;
        font-size: 11px;
        background-color: #ffffff;
        float: right;
        border-color: #ffffff;
        box-shadow: 1px 0px 10px #e5e5e5;
    }

    .btnsavesearching:hover {
        color: #e97126;
        border-color: #ffffff;
        background-color: #ffffff;
    }

    .formaccountsearch {
        padding: 2px;
        height: 25px;
        min-height: 0px;
    }

    .save {
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 5px;
        padding-bottom: 2px;
        background-color: #018ffb;
        font-size: 11px;
        font-family: PromptMedium;
        box-shadow: 1px 0px 10px #e5e5e5;
    }

    .cancel {
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 5px;
        padding-bottom: 2px;
        background-color: #ffffff;
        color: #feb018;
        font-size: 11px;
        font-family: PromptMedium;
        box-shadow: 1px 0px 10px #e5e5e5;
    }

    .panel-clrom {
        background-color: #ededed;
        border: 5px 5px 0px 0px;
    }

    .panel-clrom.on {
        /*background-color: #fecfb1;*/
        /*background-color: #fecfb1;*/
        background-color: #ededed;
        border: 5px 5px 0px 0px;
    }

    .panel-headingom a:before {
        font-family: "Glyphicons Halflings";
        /*content: "\e114";*/
        content: "▲";
        float: left;
        transition: all 0.5s;
    }
    .panel-headingom.active a:before {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-bodyom {
        height: 100%;
        background-color: #f7f7f7;
        border: 1px solid #ededed;
        /*overflow: auto;*/
        border-radius: 0px 0px 5px 5px;
    }

    .col-form-labelom {
        font-size: 11px;
        color: #2b2b2b;
    }

    .col-sm-9om {
        background-color: #ffffff;
    }

    .form-controlom {
        min-height: 25px;
        height: 0px;
        padding: 0px;
    }

    .idhead a {
        color: #2b2b2b;
        font-size: 12px;
        font-family: PromptMedium;
        font-weight: bold;
        letter-spacing: 1px;
        word-spacing: 3px;
        text-decoration: none;
    }

    .item {
        display: none;
    }

    .table.tableom td,
    .table.tableom th {
        padding: 5px;
        vertical-align: unset;
    }

    .table.tableom th,
    .table.tableom thead th {
        border: 1px solid #ededed;
        background-color: #f7f7f7;
    }

    .table.tableom tbody {
        background-color: #ffffff;
        font-size: 11px;
        font-family: PromptMedium;
        color: #2b2b2b;
    }

    tr.border_bottom td {
        border-bottom: 1px solid #ffffff;
    }

    tr.border_top td {
        border: 1px solid #ededed;
    }

    .table.tableom tfoot {
        background-color: #ffffff;
    }

    .input-group-append .btn,
    .input-group-prepend .btn {
        z-index: unset;
    }

    .k-editor,
    .k-grid,
    .k-menu,
    .k-scheduler {
        /*height: 250px;*/
    }

    .disabled {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
        color: black;
        opacity: 0.5;
    }

    .k-tooltip {
        background: #ffffff !important;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        /*width: 100%;
        height: 100%;*/
    }

    .k-callout {
        /*position:absolute;
    width:0;
    height:0;
    border-style:solid;
    border-width:6px;
    border-color:transparent;*/
        border-left-color: #ffffff;
        /*border-right-color: #ffffff;*/
        /*pointer-events:none,*/
    }

    .k-tooltip-content {
        text-align: left;
    }

    .k-grid tr {
        background-color: #ffffff;
    }

    .k-grid tr:hover {
        background-color: #fef0e7;
    }

    .k-grid tr td {
        border-bottom: 1px solid #ededed;
        border-color: #ededed;
    }

    .k-grid-header .k-link:link,
    .k-grid-header .k-link:visited,
    .k-grid-header .k-nav-current.k-state-hover .k-link,
    .k-grouping-header .k-link {
        color: #2b2b2b;
        font-family: PromptMedium;
        font-size: 12px;
        letter-spacing: 0.1px;
        line-height: 1.2;
        font-weight: 500;
        font-style: normal;
    }

    .k-grid td {
        font-family: PromptMedium;
        color: #2b2b2b;
        font-weight: 400;
        font-style: normal;
        line-height: 1.2;
    }

    .k-filter-row th,
    .k-grid-header th.k-header {
        font-family: PromptMedium;
        font-size: 12px;
        color: #2b2b2b;
        line-height: 1.2;
        font-weight: 500;
        font-style: normal;
    }

    .k-grid-header th.k-header {
        vertical-align: unset;
    }

    /*.k-filter-row th,
    .k-grid-header th.k-header {
        text-align: center;
    }*/

    #file-chosen {
        margin-left: 0.3rem;
        font-family: PromptMedium;
        position: absolute;
        min-height: 25px;
        border-radius: 0.25rem;
        font-size: 11px;
        background-color: #ededed;
        height: 25px;
        line-height: 13px;
    }

    /* Style the tab */
    .tab {
        float: left;
        border: 1px solid #ededed;
        background-color: #ffffff;
        width: 30%;
        height: 100%;
        border-radius: 5px 5px 0px 0px;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        display: block;
        background-color: inherit;
        color: #2b2b2b;
        padding: 10px 16px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        transition: 0.3s;
        font-family: PromptMedium;
        font-weight: 400;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #fef0e7;
    }

    /* Create an active/current "tab button" class */
    .tab button.active {
        background-color: #fef0e7;
    }

    /* Style the tab content */
    /*.tabcontent {
        float: left;
        padding: 0px 12px;
        border: 0px solid #ccc;
        width: 70%;
        border-left: none;
        height: 598px;
    }*/

    .tabcontent {
        float: left;
        padding: 0px 12px;
        border: 0px solid #ccc;
        width: 100%;
        border-left: none;
        height: 598px;
    }


    .vl {
        border-left: 1px solid #ededed;
        height: 100%;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nan-km .nav-link.active {
        background-color: #fff;
        color: #2b2b2b;
        border: 0px;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);
        width: 190px;
        text-align: center;
        padding: 15px 0px 15px 0px;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nan-km .nav-link {
        padding: 15px 0px 15px 0px;
        width: 190px;
        text-align: center;
        border: 0;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);
        border-radius: 5px 5px 0px 0px;
        background-color: #ededed;
        color: #a9a9a9;
        font-size: 16px;
        font-family: PromptMedium;
        font-weight: 700;
    }

    .k-filter-row .k-dropdown-operator {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #009efb;
        color: #ffffff;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffffff;
    }

    .btn.active:focus, .btn:active:focus, .btn:focus {
        outline: none;
        outline-offset: 0px;
    }

    .modal-dialog-full-width {
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        max-width:none !important;

    }

    .modal-content-full-width  {
        height: auto !important;
        min-height: 100% !important;
        border-radius: 0 !important;
        /*background-color: #ececec !important */
    }

    .modal-header-full-width  {
        border-bottom: 1px solid #9ea2a2 !important;
    }

    .modal-footer-full-width  {
        border-top: 1px solid #9ea2a2 !important;
    }

    .color-red {
      color: #e97126 !important;
      /*background-color: #000;*/
    }

    .k-filter-row .k-dropdown-operator, .k-filtercell>span>.k-button {
        display: none;
    }

    .k-grid .k-state-selected {
        background-color: #428bca;
    }

    .btn-outline-secondary {
        border-color: #ced4da;
    }

    /*.select2-container .select2-selection--multiple {
        min-height: 20px;
        height: 30px;
    }*/

    .k-grid-content {
        /*height: 162.676px;*/
        font-size: 11px;
    }

    .nav-tabs .nav-link {
       border: 0px;
       border-top-left-radius: 5px;
       border-top-right-radius: 5px;
    }

    .nav-tabs .nav-link:hover {
        border: 0px;
    }

    /*.actual-btn label{
  background-color: indigo;
  color: white;
  padding: 0.5rem;
  font-family: sans-serif;
  border-radius: 0.3rem;
  cursor: pointer;
  margin-top: 1rem;
}*/

    /*.k-callout-s {
     border-top-color: red;
}

.k-callout-n {
     border-top-color: red;
}

.k-callout-w {
     border-top-color: red;
     }*/
</style>

<script type="text/javascript">
    $(document).ready(function(){
        // $(".panel-clr").click(function () {
        //     $(this).toggleClass("on");
        // });

        // $(".panel-collapse").on("show.bs.collapse", function () {
        //     $(this).siblings(".panel-heading").addClass("active");
        // });

        // $(".panel-collapse").on("hide.bs.collapse", function () {
        //     $(this).siblings(".panel-heading").removeClass("active");
        // });

        var datasource = {
            pageSize: 10,
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
            height: 550,
        };
        var columns = [
            {
                field: "accountname",
                title: "Account Name",
                width: 100,
            },
            {
                field: "phone",
                title: "หมายเลขโทรศัพท์",
                width: 100,
            },
            {
                field: "full_name",
                title: "ชื่อ - นามสกุล",
                width: 100,
            },
            {
                field: "contact_no",
                title: "Contact ID",
                width: 100,
            },
            {
                field: "line_id",
                title: "Line ID",
                width: 100,
            },
            {
                field: "facebook",
                title: "Facebook",
                width: 100,
            }
            
        ];

        $("#grid").genKendoGridPopup(datasource, columns);

        var url = "<?php echo site_url('home/getcontact'); ?>";

        $.ajax(url, {
            type: "POST",
            data: "",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    $("#grid").data("kendoGrid").dataSource.data([]);
                    var grid = $("#grid").data("kendoGrid");
                    $.each(result.data, function (key, value) {
                        grid.dataSource.add({
                            phone: value.mobile,
                            full_name: value.full_name,
                            firstname: value.firstname,
                            contact_no: value.contact_no,
                            line_id: value.line_id,
                            facebook: value.facebook,
                            contactid: value.contactid,
                            con_contactstatus: value.con_contactstatus,
                            lastname: value.lastname,
                            email: value.email,
                            remark: value.remark,
                            register_date: value.modifiedtime,
                            accountname: value.accountname,
                            branch: value.branch,
                            taxpayer_identification_no_bill_to: value.taxpayer_identification_no_bill_to,
                            mobile: value.mobile,
                            address: value.address,
                            contact_person: value.contact_person,
                            contact_tel: value.contact_tel,
                            mailing_address: value.mailing_address,
                            contact_type: value.contact_type_details,
                            emotion_details: value.emotion_details,
                            accountid: value.accountid,
                            con_thainametitle: value.con_thainametitle,
                            bill_to_address: value.bill_to_address,
                            account_phone: value.account_phone,
                            corporate_registration_number_crn: value.corporate_registration_number_crn,
                        });
                        grid.refresh();
                    });
                } else {
                    $("#grid").data("kendoGrid").dataSource.data([]);
                }
            },
            error: function (data) {
                console.log("f");
            },
        });

        $.filltercontact = function() {
            //console.log("filltercontact");
            var formData = $("#form_account").serialize();
            //console.log(formData);
            var url = "<?php echo site_url('home/getcontact'); ?>";
            $.ajax(url, {
                type: "POST",
                data: formData,
                success: function (data) {
                    var result = jQuery.parseJSON(data);
                    console.log(result.data);
                    if (result["Type"] == "S") {
                        $("#grid").data("kendoGrid").dataSource.data([]);
                        var grid = $("#grid").data("kendoGrid");
                        
                        $.each(result.data, function (key, value) {
                            grid.dataSource.add({
                                phone: value.mobile,
                                full_name: value.full_name,
                                firstname: value.firstname,
                                contact_no: value.contact_no,
                                line_id: value.line_id,
                                facebook: value.facebook,
                                contactid: value.contactid,
                                con_contactstatus: value.con_contactstatus,
                                lastname: value.lastname,
                                email: value.email,
                                remark: value.remark,
                                register_date: value.modifiedtime,
                                accountname: value.accountname,
                                branch: value.branch,
                                taxpayer_identification_no_bill_to: value.taxpayer_identification_no_bill_to,
                                mobile: value.mobile,
                                address: value.address,
                                contact_person: value.contact_person,
                                contact_tel: value.contact_tel,
                                mailing_address: value.mailing_address,
                                contact_type: value.contact_type_details,
                                emotion_details: value.emotion_details,
                                accountid: value.accountid,
                                con_thainametitle: value.con_thainametitle,
                                bill_to_address: value.bill_to_address,
                                account_phone: value.account_phone,
                            });
                        });
                    } else {
                        $("#grid").data("kendoGrid").dataSource.data([]);
                    }
                },
                error: function (data) {
                    console.log("f");
                },
            });
        }

        var datasourcefaq = {
            pageSize: 10,
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

        var columnsfaq = [
            {
                field: "into",
                title: "More info",
                template: "<div onclick='$.clickPopupFaq(this);'><a>#if(faqid){# <i class='fa fa-info-circle k-grid-into'></i> #}else{# <i class='fa fa-info-circle k-grid-into'></i>  #}#</a></div>",
                width: 20,
            },
            {
                field: "setype",
                title: "<?php echo genLabel('LBL_FAQ_TYPE') ?>",
                width: 40,
            },
            {
                field: "faq_name",
                title: "<?php echo genLabel('LBL_FAQ_NAME') ?>",
                width: 100,
            },
            {
                field: "faq_count",
                title: "<?php echo genLabel('LBL_FAQ_COUNT') ?>",
                // template: "<span id='displayCount'>0</span>",
                width: 40,
            },
        ];

        $("#grid_faq").genKendoGrid(datasourcefaq, columnsfaq);

        var formDatafaq = $("#form_faq").serialize();
        var urlfaq = "<?php echo site_url('home/getfaq'); ?>";

        $.ajax(urlfaq, {
            type: "POST",
            data: formDatafaq,
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    $("#grid_faq").data("kendoGrid").dataSource.data([]);
                    var gridfaq = $("#grid_faq").data("kendoGrid");
                    $.each(result.data, function (key, value) {
                        gridfaq.dataSource.add({ 
                            faqstatus: value.faqstatus, 
                            setype: value.setype, 
                            faq_name: value.faq_name, 
                            faqcategories: value.faqcategories, 
                            faq_no: value.faq_no, 
                            faq_answer: value.faq_answer,
                            faqid: value.faqid, 
                            faq_count: value.faq_count,
                            crmid: value.crmid,
                        });
                    });

                    // $("#grid_faq")
                    //     .kendoTooltip({
                    //         filter: ".k-grid-into",
                    //         position: "left",
                    //         // content: function(e){
                    //         //   return '<p style="color: #000;"></p>';
                    //         // }
                    //         content: function (e) {
                    //             var dataItem = $("#grid_faq").data("kendoGrid").dataItem(e.target.closest("tr"));
                    //             var content = dataItem.faq_name;
                    //             console.log(content);
                    //             // console.log(dataItem.faq_name);
                    //             return '<p style="color: #000;">' + content + "</p>";
                    //         },
                    //     })
                    //     .data("kendoTooltip");

                    // var clicks = 0;

                    $.clickPopupFaq = function (e) {
                        // console.log("faq");

                        // clicks += 1;
                        // document.getElementById("displayCount").innerHTML = clicks;

                        var modalPopupFaq = document.getElementById("modalPopupFaq");
                        var closepopupfaq = document.getElementById("closepopupfaq");

                        modalPopupFaq.style.display = "block";

                        closepopupfaq.onclick = function () {
                            modalPopupFaq.style.display = "none";
                            $.post(site_url("home/getfaq"),function (rs) {
                                // console.log(rs);
                                $("#grid_faq").data("kendoGrid").dataSource.data([]);
                                   var grid_faq = $("#grid_faq").data("kendoGrid");
                                    $.each(rs.data, function (key, value) {
                                        grid_faq.dataSource.add({
                                            faqstatus: value.faqstatus, 
                                            setype: value.setype, 
                                            faq_name: value.faq_name, 
                                            faqcategories: value.faqcategories, 
                                            faq_no: value.faq_no, 
                                            faq_answer: value.faq_answer,
                                            faqid: value.faqid, 
                                            faq_count: value.faq_count,
                                            crmid: value.crmid,
                                        });
                                    });
                            },"json");
                        };

                        window.onclick = function (event) {
                            if (event.target == modalPopupFaq) {
                                modalPopupFaq.style.display = "none";
                            }
                        };

                        var grid = $("#grid_faq").data("kendoGrid");
                        var dataItem = grid.dataItem($(e).closest("tr"));
                        console.log(dataItem);

                        if (dataItem.faqid) {
                            // console.log(dataItem.faqid);

                            var urlcontviewfaq = "<?php echo site_url('home/countview_faq'); ?>";

                            // var faqid = dataItem.faqid;
                            // console.log(faqid);
                            console.log(dataItem.crmid);

                            var data = {
                                crmid : dataItem.crmid,
                            }

                            $.ajax(urlcontviewfaq, {
                                type: "POST",
                                data: data,
                                success: function (data) {
                                    var result = jQuery.parseJSON(data);
                                    console.log(result.data);
                                    if (result["Type"] == "S") {
                                        console.log("S");

                                    } else {
                                        console.log("E");
                                    }
                                },
                                error: function (data) {
                                    console.log("f");
                                },
                            });

                        }

                        var spanfaqno = document.getElementById("faqno");
                        spanfaqno.textContent = dataItem.faq_no;

                        var spanfaqname = document.getElementById("faqname");
                        spanfaqname.textContent = dataItem.faq_name;

                        var spanfaqdetail = document.getElementById("faqdetail");
                        spanfaqdetail.textContent = dataItem.faq_answer;

                    };

                } else {
                    $("#grid_faq").data("kendoGrid").dataSource.data([]);
                }
            },
            error: function (data) {
                console.log("f");
            },
        });

        var datasourcekm = {
            pageSize: 10,
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

        var columnskm = [
            {
                field: "star",
                title: " ",
                template: "<div onclick='$.clickfavKm(this);'><a>#if(flag){# <i class='fa fa-star'></i> #}else{# <i class='fa fa-star-o k-grid-star'></i>  #}#</a></div>",
                width: 25,
            },
            {
                field: "into",
                title: "More info",
                template: "<div onclick='$.clickPopupKm(this);'><a>#if(knowledgebaseid){# <i class='fa fa-info-circle k-grid-into'></i> #}else{# <i class='fa fa-info-circle k-grid-into'></i>  #}#</a></div>",

                width: 40,
            },
            {
                field: "knowledgebase_no",
                title: "KM No.",
                width: 65,
            },
            {
                field: "knowledgebase_name",
                title: "Desciption",
                width: 100,
            },
            {
                field: "know_category",
                title: "KM Type",
                width: 40,
            },
            {
                field: "knowledge_base_count_view",
                title: "KM Count",
                width: 40,
            },
            {
                field: "ref",
                title: "Ref",
                // template: "<i class='fa fa-chain-broken k-grid-ref'></i>",
                template: "<div onclick='$.cellClick(this);'><i class='fa fa-chain-broken k-grid-ref'></i></div>",
                width: 25,
            },
        ];

        $("#grid_km").genKendoGrid(datasourcekm, columnskm);

        $.cellClick = function (e) {
            console.log("clickRef");
            var grid = $("#grid_km").data("kendoGrid");
            var dataItem = grid.dataItem($(e).closest("tr"));
            $("#descrip_case").val(dataItem.knowledgebase_name);
        };


        var formDatakm = $("#form_km").serialize();
        var urlkm = "<?php echo site_url('home/getkm'); ?>";

        $.ajax(urlkm, {
            type: "POST",
            data: formDatakm,
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    $("#grid_km").data("kendoGrid").dataSource.data([]);
                    var gridkm = $("#grid_km").data("kendoGrid");
                    $.each(result.data, function (key, value) {
                        gridkm.dataSource.add({
                            knowledgebaseid: value.knowledgebaseid,
                            know_remark: value.know_remark,
                            knowledgebase_no: value.knowledgebase_no,
                            knowledgebase_name: value.knowledgebase_name,
                            know_category: value.know_category,
                            presence: value.presence,
                            status: value.status,
                            knowledge_base_count_view: value.knowledge_base_count_view,
                            crmid: value.crmid,
                            know_detail: value.know_detail,
                            flag: value.flag,
                            // userid: value.userid,
                        });
                    });

                    $.clickPopupKm = function (e) {
                        console.log("clickInfo");

                        var modalpopupkm = document.getElementById("modalPopupKm");
                        var closepopupkm = document.getElementById("closepopupkm");

                        modalpopupkm.style.display = "block";

                        closepopupkm.onclick = function () {
                            modalpopupkm.style.display = "none";
                            $.post(site_url("home/getkm"),function (rs) {
                                // console.log(rs);
                                $("#grid_km").data("kendoGrid").dataSource.data([]);
                                   var grid_km = $("#grid_km").data("kendoGrid");
                                    $.each(rs.data, function (key, value) {
                                        grid_km.dataSource.add({
                                            knowledgebaseid: value.knowledgebaseid,
                                            know_remark: value.know_remark,
                                            knowledgebase_no: value.knowledgebase_no,
                                            knowledgebase_name: value.knowledgebase_name,
                                            know_category: value.know_category,
                                            presence: value.presence,
                                            status: value.status,
                                            knowledge_base_count_view: value.knowledge_base_count_view,
                                            crmid: value.crmid,
                                            know_detail: value.know_detail,
                                            flag: value.flag,
                                        });
                                    });
                            },"json");
                        };

                        window.onclick = function (event) {
                            if (event.target == modalpopupkm) {
                                modalpopupkm.style.display = "none";
                            }
                        };

                        var grid = $("#grid_km").data("kendoGrid");
                        var dataItem = grid.dataItem($(e).closest("tr"));
                        console.log(dataItem);

                        if (dataItem.knowledgebaseid) {
                            // console.log(dataItem.knowledgebaseid);
                            var urlcountviewkm = "<?php echo site_url('home/countview_km'); ?>";

                            var data = {
                                crmid : dataItem.crmid,
                            }

                            $.ajax(urlcountviewkm, {
                                type: "POST",
                                data: data,
                                success: function (data) {
                                    var result = jQuery.parseJSON(data);
                                    console.log(result.data);
                                    if (result["Type"] == "S") {
                                        console.log("S");

                                    } else {
                                        console.log("E");
                                    }
                                },
                                error: function (data) {
                                    console.log("f");
                                },
                            });

                        }

                        var spanknowledgebasename = document.getElementById("knowledgebase_name");
                        spanknowledgebasename.textContent = dataItem.knowledgebase_name;

                        var spanknowdetail = document.getElementById("know_detail");
                        spanknowdetail.textContent = dataItem.know_detail;

                    };


                    $.clickfavKm = function(e) {

                        var grid = $("#grid_km").data("kendoGrid");
                        var dataItem = grid.dataItem($(e).closest("tr"));
                        // console.log(dataItem.flag);

                        if (dataItem.flag) {

                            dataItem.set("flag",false);
                            if (dataItem.flag == false) {
                                console.log(dataItem.flag);
                                // console.log(dataItem.knowledgebaseid);
                                var urlfavkm = "<?php echo site_url('home/addfavorite'); ?>";

                                var data = {
                                    crmid : dataItem.knowledgebaseid,
                                    flag: dataItem.flag,
                                }

                                $.ajax(urlfavkm, {
                                    type: "POST",
                                    data: data,
                                    success: function (data) {
                                        var result = jQuery.parseJSON(data);
                                        console.log(result.data);
                                        if (result["Type"] == "S") {
                                            console.log("S");

                                        } else {
                                            console.log("E");
                                        }
                                    },
                                    error: function (data) {
                                        console.log("f");
                                    },
                                });
                            }

                        } else {

                            dataItem.set("flag",true);
                            // console.log(dataItem.flag);
                            if (dataItem.flag == true) {
                                console.log(dataItem.flag);
                                // console.log(dataItem.knowledgebaseid);

                                var urlfavkmtrue = "<?php echo site_url('home/addfavorite'); ?>";

                                var data = {
                                    crmid : dataItem.knowledgebaseid,
                                    flag : dataItem.flag,
                                }

                                $.ajax(urlfavkmtrue, {
                                    type: "POST",
                                    data: data,
                                    success: function(data) {
                                        var result = jQuery.parseJSON(data);
                                        console.log(result.data);
                                        if (result["Type"] == "S") {
                                            console.log("S");
                                        } else {
                                            console.log("E");
                                        }
                                    },
                                    error: function (data) {
                                        console.log("f");
                                    },
                                });
                            }

                        }

                    }

                    // $("#grid_km").kendoTooltip({
                    //     filter: ".k-grid-into",
                    //     position: "left",
                    //     content: function (e) {
                    //         var dataItem = $("#grid_km").data("kendoGrid").dataItem(e.target.closest("tr"));
                    //         var content = dataItem.knowledgebase_name;
                    //         console.log(content);
                    //         // console.log(dataItem.faq_name);
                    //         return '<p style="color: #000;">' + dataItem.knowledgebase_name + '</p><p style="color: #000;">' + dataItem.know_category + "</p>";
                    //     },
                    // });
                } else {
                    $("#grid_km").data("kendoGrid").dataSource.data([]);
                }
            },
            error: function (data) {
                console.log("f");
            },
        });

        var totalproduct;

        calculator_order = function() {

            var number1 =  document.getElementById('number1');

            // console.log(number1.value);
            //var price = 1500;
            //$("#priceperunit1").val(price.toFixed(2));
            // var c_price_vat = document.getElementById('c_price_vat');
   //          console.log(c_price_vat.value);
            var priceperunit1 = document.getElementById('priceperunit1');
            // priceperunit1.value = c_price_vat.value;
            console.log(priceperunit1.value);
            var price = parseInt(priceperunit1.value);
            var tot_price = number1.value * price;
            var total = tot_price;
            $("#amount1").val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            // var purchaseamount1 = document.getElementById("purchaseamount1");
            // purchaseamount1.textContent = total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            // console.log(total);

            // var c_cost = $("#c_cost").val();
            // console.log(parseFloat(c_cost) + 1);
            // var ccost = c_cost.replace(",","");
            // console.log(parseFloat(ccost));
            // var ccost = 1406;
            var afterdiscount1 = document.getElementById("afterdiscount1");
            afterdiscount1.value;

            totalproduct = number1.value * afterdiscount1.value;
            $('#purchaseamount1').val(totalproduct);
            var purchaseamount1show = document.getElementById('purchaseamount1show');
            purchaseamount1show.textContent = totalproduct.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

            // var hasChanged;
            // var discount = document.getElementById('discount');

            // discount.onchange = function () { hasChanged = true; }
            // discount.onblur = function() {
            //     if (hasChanged) {
            //         // console.log('r');
            //         afterdiscount1.value = 0;
            //         purchaseamount1.textContent = 0;
                    
            //     }
            // }

            // console.log(afterdiscount1.value);

            var number2 = document.getElementById('number2');
            var priceperunit2 = document.getElementById('priceperunit2');
            // console.log(priceperunit2.value);
            var sumnumber2 = number2.value * priceperunit2.value;
            var total2 = sumnumber2;
            $("#amount2").val(total2.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var number3 =  document.getElementById('number3');
            var priceperunit3 = document.getElementById('priceperunit3');
            // console.log(number3.value);
            // var pricenumber3 = 12900;
            var sumnumber3 = number3.value * priceperunit3.value;
            var total3 = sumnumber3;
            $("#amount3").val(total3.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var number4 = document.getElementById('number4');
            var priceperunit4 = document.getElementById('priceperunit4');
            // console.log(number4.value);
            // var pricenumber4 = 107;
            var sumnumber4 = number4.value * priceperunit4.value;
            var total4 = sumnumber4;
            $("#amount4").val(total4.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            var sumtotal1 = total + total2 + total3 + total4;
            var vatsumtotal1 = (sumtotal1 * 7)/107;
            var total1sub =  sumtotal1 + vatsumtotal1;

            var total1sum = document.getElementById('Total1show');
            total1sum.textContent = total1sub.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            $("#Total1").val(total1sub.toFixed(2));
            // console.log(sumtotal1);

           
            var vat1 = document.getElementById('Vat1show');
            vat1.textContent = vatsumtotal1.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            $("#Vat1").val(vatsumtotal1.toFixed(2));
            // console.log(vatsumtotal1);

            
            var subTotal1show = document.getElementById('subTotal1show');
            subTotal1show.textContent = sumtotal1.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            $("#subTotal1").val(sumtotal1.toFixed(2));
            // console.log(total1sub);

            // var sumtotaldiscount = totalproduct + totalproduct2 + totalproduct3 + totalproduct4;
            // // console.log(sumtotaldiscount);
            // var subTotal2text = document.getElementById('subTotal2');
            // subTotal2text.textContent = sumtotaldiscount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

            // var vatsumtotaldiscount = (sumtotaldiscount * 0.07);
            // // console.log(vatsumtotaldiscount);
            // var vat2 = document.getElementById('Vat2');
            // vat2.textContent = vatsumtotaldiscount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

            // var sumtotal2 = sumtotaldiscount + vatsumtotaldiscount;
            // var total2text = document.getElementById('Total2');
            // // console.log(sumtotaldiscount + vatsumtotaldiscount);
            // total2text.textContent = sumtotal2.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');


        }

        set_discount = function() {
            var discount = document.getElementById('discount');
            // console.log(discount.value);
            var afterdiscount1 = document.getElementById('afterdiscount1');
            afterdiscount1.value = 0;
            var purchaseamount1show = document.getElementById('purchaseamount1show');
            purchaseamount1show.textContent = 0;

            $('#purchaseamount1').val(parseInt(afterdiscount1.value));
        }

        set_afterdiscount1 = function() {
            var afterdiscount1 = document.getElementById('afterdiscount1');
            console.log(afterdiscount1.value);
            var number1 = document.getElementById('number1');
            var totalproduct = number1.value * afterdiscount1.value;
            var purchaseamount1show = document.getElementById('purchaseamount1show');
            purchaseamount1show.textContent = totalproduct.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

            $('#purchaseamount1').val(totalproduct);
        }

        // set_afterdiscount12 = function() {
        //    var afterdiscount1 = document.getElementById('afterdiscount1');
        //    console.log(afterdiscount1.value);
        //    var purchaseamount1show = document.getElementById('purchaseamount2show');
        //    purchaseamount1show.textContent = parseInt(afterdiscount1.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        //    $('#purchaseamount1').val(parseInt(afterdiscount1.value));
        // }

        set_afterdiscount2 = function() {
           var afterdiscount2 = document.getElementById('afterdiscount2');
           // console.log(afterdiscount2.value);
           var purchaseamount2show = document.getElementById('purchaseamount2show');
           purchaseamount2show.textContent = parseInt(afterdiscount2.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
           $('#purchaseamount2').val(parseInt(afterdiscount2.value));
        }

        set_afterdiscount3 = function() {
            var afterdiscount3 = document.getElementById('afterdiscount3');

            var purchaseamount3show = document.getElementById('purchaseamount3show');
            purchaseamount3show.textContent = parseInt(afterdiscount3.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

            $('#purchaseamount3').val(parseInt(afterdiscount3.value).toFixed(2));
        }

        set_afterdiscount4 = function() {
            var afterdiscount4 = document.getElementById('afterdiscount4');
            var purchaseamount4show = document.getElementById('purchaseamount4show');
            purchaseamount4show.textContent = parseInt(afterdiscount4.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

            $('#purchaseamount4').val(parseInt(afterdiscount4.value));
        }

        set_calculator = function() {

            var purchaseamount1 = document.getElementById('purchaseamount1');
            // console.log(parseInt(purchaseamount1.value) || 0);
            var purchaseamount1sum = parseInt(purchaseamount1.value) || 0;
            var purchaseamount2 = document.getElementById('purchaseamount2');
            var purchaseamount2sum = parseInt(purchaseamount2.value) || 0;
            // console.log(parseInt(purchaseamount2.value) || 0);
            var purchaseamount3 = document.getElementById('purchaseamount3');
            var purchaseamount3sum = parseInt(purchaseamount3.value) || 0;
            var purchaseamount4 = document.getElementById('purchaseamount4');
            var purchaseamount4sum = parseInt(purchaseamount4.value) || 0;

            var sumsubTotal2 = purchaseamount1sum + purchaseamount2sum + purchaseamount3sum + purchaseamount4sum;
            console.log(sumsubTotal2);
            var subTotal2show = document.getElementById('subTotal2show');
            subTotal2show.textContent = sumsubTotal2.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            $("#subTotal2").val(sumsubTotal2.toFixed(2));

            var vatsumtotaldiscount = (sumsubTotal2 * 0.07);
            // console.log(vatsumtotaldiscount);
            var vat2show = document.getElementById('Vat2show');
            vat2show.textContent = vatsumtotaldiscount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            $("#Vat2").val(vatsumtotaldiscount.toFixed(2));


            var sumtotal2 = sumsubTotal2 + vatsumtotaldiscount;
            var total2text = document.getElementById('Total2show');
            // console.log(sumtotaldiscount + vatsumtotaldiscount);
            total2text.textContent = sumtotal2.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            $("#Total2").val(sumtotal2.toFixed(2));

        }

        // $(document).on("click", ".fillterfaq", function () {
        //     var formDatafaq = $("#form_faq").serialize();
        //     var urlfaq = "<?php echo site_url('home/getfaq'); ?>";

        //     $.ajax(urlfaq, {
        //         type: "POST",
        //         data: formDatafaq,
        //         success: function (data) {
        //             var result = jQuery.parseJSON(data);
        //             console.log(result.data);
        //             if (result["Type"] == "S") {
        //                 $("#grid_faq").data("kendoGrid").dataSource.data([]);
        //                 var gridfaq = $("#grid_faq").data("kendoGrid");
        //                 $.each(result.data, function (key, value) {
        //                     gridfaq.dataSource.add({ faqstatus: value.faqstatus, setype: value.setype, faq_name: value.faq_name, faqcategories: value.faqcategories });
        //                 });
        //             } else {
        //                 $("#grid_faq").data("kendoGrid").dataSource.data([]);
        //             }
        //         },
        //         error: function (data) {
        //             console.log("f");
        //         },
        //     });
        // });        

        // $(document).on("click", ".fillterkm", function () {
        //     var formDatakm = $("#form_km").serialize();
        //     var urlkm = "<?php echo site_url('home/getkm'); ?>";

        //     $.ajax(urlkm, {
        //         type: "POST",
        //         data: formDatakm,
        //         success: function (data) {
        //             var result = jQuery.parseJSON(data);
        //             console.log(result.data);
        //             if (result["Type"] == "S") {
        //                 $("#grid_km").data("kendoGrid").dataSource.data([]);
        //                 var gridkm = $("#grid_km").data("kendoGrid");
        //                 $.each(result.data, function (key, value) {
        //                     gridkm.dataSource.add({
        //                         knowledgebaseid: value.knowledgebaseid,
        //                         know_remark: value.know_remark,
        //                         knowledgebase_no: value.knowledgebase_no,
        //                         knowledgebase_name: value.knowledgebase_name,
        //                         know_category: value.know_category,
        //                         presence: value.presence,
        //                         status: value.status,
        //                     });
        //                 });
        //             } else {
        //                 $("#grid_km").data("kendoGrid").dataSource.data([]);
        //             }
        //         },
        //         error: function (data) {
        //             console.log("f");
        //         },
        //     });
        // });

        var datasourcehistorycase = {
            pageSize: 5,
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

        var columnshistorycase = [
            {
                field: "info",
                title: "More info",
                template: "<div onclick='$.clickPopupHistoryCase(this);'><i class='fa fa-info-circle k-grid-info'></i></div>",
                width: 70,
            },
            {
                field: "ticket_no",
                title: "Case No.",
                width: 100,
            },
            {
                field: "contact_no",
                title: "Contact ID",
                width: 100,
            },
            {
                field: "firstname",
                title: "Name",
                width: 100,
            },
            {
                field: "case_date",
                title: "Case Date",
                template: "#= kendo.toString(kendo.parseDate(case_date, 'yyyy-MM-dd'), 'dd/MM/yyyy') #",
                width: 100,
            },
            {
                field: "task_name",
                title: "Task Category",
                width: 100,
            },
            {
                field: "case_status",
                title: "Status",
                width: 70,
            },
            {
                field: "description",
                title: "Description",
                width: 100,
            },
            {
                field: "user_name",
                title: "Handled by",
                width: 100,
            },
        ];

        $("#grid_historycase").genKendoGrid(datasourcehistorycase, columnshistorycase);

        var datasourcehistoryorder = {
            pageSize: 5,
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
            height: 550,
        };

        var columnshistoryorder = [
            {
                field: "orderno",
                title: "Order No.",
                width: 100,
            },
            {
                field: "project_address",
                title: "Project / Address",
                width: 100,
            },
            {
                field: "plant_name",
                title: "Plant Name",
                width: 100,
            },
            {
                field: "order_status_order",
                title: "Status",
                width: 100,
            },
        ];

        $("#grid_historyorder").genKendoGrid(datasourcehistoryorder, columnshistoryorder);

        // $(document).on("click", ".filltercontact", function () {

        //     console.log("clickse");
        //     var formData = $("#form_account").serialize();
        //     // var url ="<?php echo site_url('home/getaccount'); ?>";
        //     var url = "<?php echo site_url('home/getcontact'); ?>";

        //     console.log(formData);

        //     $.ajax(url, {
        //         type: "POST",
        //         data: formData,
        //         success: function (data) {
        //             var result = jQuery.parseJSON(data);
        //             console.log(result.data);
        //             if (result["Type"] == "S") {
        //                 $("#grid").data("kendoGrid").dataSource.data([]);
        //                 var grid = $("#grid").data("kendoGrid");
        //                 $.each(result.data, function (key, value) {
        //                     grid.dataSource.add({
        //                         phone: value.phone,
        //                         firstname: value.firstname,
        //                         contact_no: value.contact_no,
        //                         line_id: value.line_id,
        //                         facebook: value.facebook,
        //                         contactid: value.contactid,
        //                         con_contactstatus: value.con_contactstatus,
        //                         lastname: value.lastname,
        //                         email: value.email,
        //                         remark: value.remark,
        //                         register_date: value.register_date,
        //                         accountname: value.accountname,
        //                         branch: value.branch,
        //                         taxpayer_identification_no_bill_to: value.taxpayer_identification_no_bill_to,
        //                         mobile: value.mobile,
        //                         address: value.address,
        //                         contact_person: value.contact_person,
        //                         contact_tel: value.contact_tel,
        //                         mailing_address: value.mailing_address,
        //                         contact_type: value.contact_type,
        //                         emotion_details: value.emotion_details,
        //                         accountid: value.accountid,
        //                     });
        //                 });
        //             } else {
        //                 $("#grid").data("kendoGrid").dataSource.data([]);
        //             }
        //         },
        //         error: function (data) {
        //             console.log("f");
        //         },
        //     });
        // });

        /*$("#grid").kendoGrid({
            dataSource: {
                type: "odata",
                transport: {
                    read: "https://demos.telerik.com/kendo-ui/service/Northwind.svc/Customers"
                },
                pageSize: 20
            },
            height: 550,
            groupable: true,
            sortable: true,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            columns: [{
                field: "ContactName",
                title: "Contact Name",
                width: 240
            }, {
                field: "ContactTitle",
                title: "Contact Title"
            }, {
                field: "CompanyName",
                title: "Company Name"
            }, {
                field: "Country",
                width: 150
            }]
        });*/

        $.clearAll = function () {
            $("#contactid").val("");
            $("#contact_code").val("");
            $("#contact_status").val("").trigger("change");
            $("#title_name").val("").trigger("change");
            $("#fristname_contact").val("");
            $("#lastname_contact").val("");
            $("#phone_contact").val("");
            $("#email_contact").val("");
            $("#lineid_contact").val("");
            $("#facebook_contact").val("");
            $("#remark_contact").val("");
            $("#natured").prop("checked", false);
            $("#normal").prop("checked", false);
            $("#morose").prop("checked", false);
            $("#sitecode_contact").val("").trigger("change");
            $("#dateupdate_contact").val("");
        };

        $.clearAllBill = function () {
            $("#companyname").val("");
            $("#accountid").val("");
            $("#brach").val("");
            $("#taxno").val("");
            $("#telephone").val("");
            $("#address").val("");
            $("#contact_person").val("");
            $("#contact_tel").val("");
            $("#address_bill_to").val("");
            $("#companyname").val("");
            $("#brach").val("");
            $("#telephone").val("");
            $("#contact_person").val("");
            $("#contact_tel").val("");
        };

        $.clearAllCase = function () {
            $("#ticketid").val("");
            $("#task_name").val("").trigger("change");
            $("#status").val("เปิดงาน").trigger("change");
            $("#contact_channel").val("Call").trigger("change");
            $("#response").val("Call").trigger("change");
            $("#assigned_user_id").val("");
            $("#date_of_execution").val("");
            $("#date_completed").val("");
            $("#description").val("");
            $("#notes").val("");
            $("#file-chosen").html("");
            $("#handled_by").val("");
            $("#case_date").val('<?php echo date('Y-m-d\Th:i'); ?>');
        };

        // $('.addToCart').click(function(e){
        //     e.preventDefault();
        //     var form = $(this).closest('form_save_case');

        //         // to access the select by tag name:
        //         if (form.find('select').val()) {

        //         // to access the select by class:
        //         // if (form.find('.variants').val()) {

        //             form.submit();
        //             saveCase();
        //         } else {
        //             // alert("You need to pick variant");
        //             console.log('t');
        //         }
        // });

        // function validateForm() {
        //       var x = document.forms["form_save_case"]["task_name"].value;
        //       if (x == "") {
        //         alert("Name must be filled out");
        //         return false;
        //     }
        // }
        $.saveCase = function (){
            $("#form_save_case").submit();
        }

        //$.saveCase = function (e) {
        $('#form_save_case').on('submit', function(e) {
            //$("#form_save_case").submit();
            e.preventDefault();

            var case_date = document.getElementById('case_date');
            console.log(case_date.value);

            var date_completed = document.getElementById('date_completed');
            console.log(date_completed.value);

            var data = $("#form_save_case").serialize();
            var accountid = $("#accountid").val();
            var contactid = $("#contactid").val();
            
            var formData = new FormData(this);

            formData.append('accountid', accountid);
            formData.append('contactid', contactid);
            //console.log(formData);
            var url = "<?php echo site_url('home/create_case'); ?>";

            var task_name = document.getElementById("task_name");

            if (!task_name.checkValidity()) {

                // document.getElementById("demo").innerHTML = task_name.validationMessage;
                $('#demo').text('* โปรดเลือกรายการจากหน้ารายการ');

            } else {
                document.getElementById("demo").style.visibility = "hidden";

                $.ajax(url, {
                    type: "POST",
                    //enctype: 'multipart/form-data',
                    cache: false,
                    contentType : false, // you can also use multipart/form-data replace of false
                    processData: false,
                    data: formData,
                    //data: data + "&accountid=" + accountid + "&contactid=" + contactid,
                    success: function (data) {
                        var result = jQuery.parseJSON(data);
                        console.log(result);
                        if (result["Type"] == "S") {
                            console.log("Yes!");

                            swal({
                                position: "center", //'top-end',
                                type: "success",
                                title: result["Message"],
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            $("#ticketid").val("");
                            $("#task_name").val("").trigger("change");
                            $("#case_status").val("เปิดงาน").trigger("change");
                            $("#contact_channel").val("Call").trigger("change");
                            $("#response").val("Call").trigger("change");
                            $("#date_of_execution").val("");
                            $("#date_completed").val("");
                            $("#descrip_case").val("");
                            $("#notes").val("");
                            $("#file-chosen").html("");
                            $("#handled_by").val("");
                            $("#file_upload").val("");
                            $("#case_date").val("<?php echo date('Y-m-d\Th:i'); ?>");
                            
                            $.post(
                                site_url("home/gethistorycase"),
                                { accountid: accountid },
                                function (rs) {
                                    // console.log(rs);

                                    $("#grid_historycase").data("kendoGrid").dataSource.data([]);
                                    var grid_historycase = $("#grid_historycase").data("kendoGrid");
                                    $.each(rs.data, function (key, value) {
                                        grid_historycase.dataSource.add({
                                            ticket_no: value.ticket_no,
                                            case_date: value.case_date,
                                            task_name: value.task_name,
                                            case_status: value.case_status,
                                            case_detail: value.case_detail,
                                            priority_case: value.priority_case,
                                            description: value.description,
                                            notes: value.notes,
                                            contact_channel: value.contact_channel,
                                            response: value.response,
                                            handled_by: value.handled_by,
                                            createdtime: value.createdtime,
                                            modifiedby: value.modifiedby,
                                            modifiedtime: value.modifiedtime,
                                            contact_name: value.contact_name,
                                            tel: value.tel,
                                            email: value.email,
                                            line_id: value.line_id,
                                            facebook: value.facebook,
                                            case_date: value.case_date,
                                            case_time: value.case_time,
                                            date_of_execution: value.date_of_execution,
                                            process_time: value.process_time,
                                            date_completed: value.date_completed,
                                            time_completed: value.time_completed,
                                            date_cancelled: value.date_cancelled,
                                            time_cancelled: value.time_cancelled,
                                            user_name: value.user_name,
                                            contact_no: value.contact_no,
                                            firstname: value.firstname,
                                            image: value.image,
                                            full_name: value.full_name,
                                            accountname: value.accountname,
                                        });
                                        
                                    });

                                    $.clickPopupHistoryCase = function(e) {
                                        // console.log("test");
                                        var modalPopuphistorycase = document.getElementById("modalPopuphistorycase");
                                        var closepopuphistorycase = document.getElementById("closepopuphistorycase");

                                        modalPopuphistorycase.style.display = "block";

                                        closepopuphistorycase.onclick = function() {
                                            modalPopuphistorycase.style.display = "none";
                                        }

                                        window.onclick = function(event) {
                                            if (event.target == modalPopuphistorycase) {
                                                modalPopuphistorycase.style.display = "none";
                                            }
                                        }

                                        var grid = $("#grid_historycase").data("kendoGrid");
                                        var dataItem = grid.dataItem($(e).closest("tr"));
                                        console.log(dataItem);

                                        $('#caseno').val(dataItem.ticket_no);
                                        $('#taskname').val(dataItem.task_name);
                                        $('#statushistorycase').val(dataItem.case_status);
                                        $('#priority').val(dataItem.priority_case);
                                        $('#description_case').val(dataItem.description);
                                        $('#notes_case').val(dataItem.notes);
                                        $('#contactchannel_case').val(dataItem.contact_channel);
                                        $('#response_case').val(dataItem.response);
                                        $('#handledby_case').val(dataItem.handled_by);
                                        $('#createdtime_case').val(dataItem.createdtime);
                                        $('#modifiedby_case').val(dataItem.modifiedby);
                                        $('#modifiedtime_case').val(dataItem.modifiedtime);

                                        $('#contact_name_case').val(dataItem.full_name);
                                        $('#account_name_case').val(dataItem.accountname);
                                        $('#tel_case').val(dataItem.tel);
                                        $('#email_case').val(dataItem.email);
                                        $('#line_id_case').val(dataItem.line_id);
                                        $('#facebook_case').val(dataItem.facebook);

                                        // $('#case_date_case').val(dataItem.case_date);
                                        // $('#case_time_case').val(dataItem.case_time);
                                        // $('#date_of_execution_case').val(dataItem.date_of_execution);
                                        // $('#process_time_case').val(dataItem.process_time);
                                        // $('#date_completed_case').val(dataItem.date_completed);
                                        // $('#time_completed_case').val(dataItem.time_completed);
                                        // $('#date_cancelled_case').val(dataItem.date_cancelled);
                                        // $('#time_cancelled_case').val(dataItem.time_cancelled);

                                        if (dataItem.case_date == "0000-00-00") {
                                            $('#case_date_case').val("");
                                        } else {
                                            $('#case_date_case').val(moment(dataItem.case_date).format('DD/MM/YYYY'));
                                        }

                                        $('#case_time_case').val(dataItem.case_time);

                                        if (dataItem.date_of_execution == "0000-00-00") {
                                            $('#date_of_execution_case').val("");
                                        } else {
                                            // $('#date_of_execution_case').val(moment(dataItem.date_of_execution).format('DD/MM/YYYY'));
                                            $('#date_of_execution_case').val(moment(dataItem.date_of_execution).format('DD/MM/YYYY'));
                                        }

                                        $('#process_time_case').val(dataItem.process_time);


                                        if (dataItem.date_completed == "0000-00-00") {
                                            $('#date_completed_case').val("");
                                        } else {
                                            $('#date_completed_case').val(moment(dataItem.date_completed).format('DD/MM/YYYY'));
                                        }

                                        $('#time_completed_case').val(dataItem.time_completed);

                                        if (dataItem.date_cancelled == "0000-00-00") {
                                            $('#date_cancelled_case').val("");
                                        } else {
                                            $('#date_cancelled_case').val(moment(dataItem.date_cancelled).format('DD/MM/YYYY'));
                                        }

                                        $('#time_cancelled_case').val(dataItem.time_cancelled);

                                        var image = dataItem.image;

                                        for (var i = 0; i < image.length; i++) {
                                            $('#file_upload_name').val(image[i]['name']);
                                            $('#file_upload_path').val(image[i]['path']);
                                        }
                                        
                                    }
                                },
                                "json"
                            );

                        } else {
                            console.log("No!");

                            swal("", result["Message"], "error");
                        }
                    },
                    error: function (data) {
                        console.log("error");
                    },
                });
            } 
        });
        /*$.saveCase = function (e) {

            $("#form_save_case").submit();
            e.preventDefault();

            var data = $("#form_save_case").serialize();
            var accountid = $("#accountid").val();
            var contactid = $("#contactid").val();
            
            var url = "<?php echo site_url('home/create_case'); ?>";

            var task_name = document.getElementById("task_name");

            if (!task_name.checkValidity()) {

                document.getElementById("demo").innerHTML = task_name.validationMessage;

            } else {
                document.getElementById("demo").style.visibility = "hidden";

                $.ajax(url, {
                    type: "POST",
                    data: data + "&accountid=" + accountid + "&contactid=" + contactid,
                    success: function (data) {
                        var result = jQuery.parseJSON(data);
                        console.log(result);
                        if (result["Type"] == "S") {
                            console.log("Yes!");

                            swal({
                                position: "center", //'top-end',
                                type: "success",
                                title: result["Message"],
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            $("#ticketid").val("");
                            $("#task_name").val("").trigger("change");
                            $("#case_status").val("เปิดงาน").trigger("change");
                            $("#contact_channel").val("Call").trigger("change");
                            $("#response").val("Call").trigger("change");
                            $("#date_of_execution").val("");
                            $("#date_completed").val("");
                            $("#descrip_case").val("");
                            $("#notes").val("");
                            $("#file-chosen").html("");
                            $("#handled_by").val("");
                            $("#case_date").val("<?php echo date('Y-m-d\Th:i'); ?>");
                            
                            $.post(
                                site_url("home/gethistorycase"),
                                { accountid: accountid },
                                function (rs) {
                                    // console.log(rs);

                                    $("#grid_historycase").data("kendoGrid").dataSource.data([]);
                                    var grid_historycase = $("#grid_historycase").data("kendoGrid");
                                    $.each(rs.data, function (key, value) {
                                        grid_historycase.dataSource.add({
                                            ticket_no: value.ticket_no,
                                            case_date: value.case_date,
                                            task_name: value.task_name,
                                            case_status: value.case_status,
                                            case_detail: value.case_detail,
                                            priority_case: value.priority_case,
                                            description: value.description,
                                            notes: value.notes,
                                            contact_channel: value.contact_channel,
                                            response: value.response,
                                            handled_by: value.handled_by,
                                            createdtime: value.createdtime,
                                            modifiedby: value.modifiedby,
                                            modifiedtime: value.modifiedtime,
                                            contact_name: value.contact_name,
                                            tel: value.tel,
                                            email: value.email,
                                            line_id: value.line_id,
                                            facebook: value.facebook,
                                            case_date: value.case_date,
                                            case_time: value.case_time,
                                            date_of_execution: value.date_of_execution,
                                            process_time: value.process_time,
                                            date_completed: value.date_completed,
                                            time_completed: value.time_completed,
                                            date_cancelled: value.date_cancelled,
                                            time_cancelled: value.time_cancelled,
                                            user_name: value.user_name,
                                            contact_no: value.contact_no,
                                            firstname: value.firstname,
                                        });
                                        
                                    });

                                    $.clickPopupHistoryCase = function(e) {
                                        // console.log("test");
                                        var modalPopuphistorycase = document.getElementById("modalPopuphistorycase");
                                        var closepopuphistorycase = document.getElementById("closepopuphistorycase");

                                        modalPopuphistorycase.style.display = "block";

                                        closepopuphistorycase.onclick = function() {
                                            modalPopuphistorycase.style.display = "none";
                                        }

                                        window.onclick = function(event) {
                                            if (event.target == modalPopuphistorycase) {
                                                modalPopuphistorycase.style.display = "none";
                                            }
                                        }

                                        var grid = $("#grid_historycase").data("kendoGrid");
                                        var dataItem = grid.dataItem($(e).closest("tr"));
                                        console.log(dataItem);

                                        $('#caseno').val(dataItem.ticket_no);
                                        $('#taskname').val(dataItem.task_name);
                                        $('#statushistorycase').val(dataItem.case_status);
                                        $('#priority').val(dataItem.priority_case);
                                        $('#description_case').val(dataItem.description);
                                        $('#notes_case').val(dataItem.notes);
                                        $('#contactchannel_case').val(dataItem.contact_channel);
                                        $('#response_case').val(dataItem.response);
                                        $('#handledby_case').val(dataItem.handled_by);
                                        $('#createdtime_case').val(dataItem.createdtime);
                                        $('#modifiedby_case').val(dataItem.modifiedby);
                                        $('#modifiedtime_case').val(dataItem.modifiedtime);

                                        $('#contact_name_case').val(dataItem.contact_name_case);
                                        $('#tel_case').val(dataItem.tel);
                                        $('#email_case').val(dataItem.email);
                                        $('#line_id_case').val(dataItem.line_id);
                                        $('#facebook_case').val(dataItem.facebook);

                                        $('#case_date_case').val(dataItem.case_date);
                                        $('#case_time_case').val(dataItem.case_time);
                                        $('#date_of_execution_case').val(dataItem.date_of_execution);
                                        $('#process_time_case').val(dataItem.process_time);
                                        $('#date_completed_case').val(dataItem.date_completed);
                                        $('#time_completed_case').val(dataItem.time_completed);
                                        $('#date_cancelled_case').val(dataItem.date_cancelled);
                                        $('#time_cancelled_case').val(dataItem.time_cancelled);

                                    }
                                },
                                "json"
                            );

                        } else {
                            console.log("No!");

                            swal("", result["Message"], "error");
                        }
                    },
                    error: function (data) {
                        console.log("error");
                    },
                });
            } 

        };*/

        $.saveContacts = function() {

            var data = $("#form_save_contacts").serialize();
            var contactid = $("#contactid").val(); 
            var actiontext = document.getElementById('action');
            var url = "<?php echo site_url('home/create_contacts'); ?>";

            var fristname_contact = document.getElementById('fristname_contact');
            var phone_contact = document.getElementById('phone_contact');

            if (!fristname_contact.checkValidity() && !phone_contact.checkValidity()) {
                $("#firstname_required").text('* โปรดกรอกฟิลด์นี้');
                $("#phone_required").text('* โปรดกรอกฟิลด์นี้');
            } else if (!fristname_contact.checkValidity()) {
                $("#firstname_required").text('* โปรดกรอกฟิลด์นี้');
            } else if (!phone_contact.checkValidity()) {
                $("#phone_required").text('* โปรดกรอกฟิลด์นี้');
            } else {
                console.log('t');
                // $("#firstname_required").text('');
                // $("#phone_required").text('');
                document.getElementById('firstname_required').style.display = 'none';
                document.getElementById('phone_required').style.display = 'none';

                if (contactid == "") {
                actiontext.value = "add";
                console.log(actiontext.value);
                } else {
                    actiontext.value = "edit";
                    console.log(actiontext.value);
                }

                var action = actiontext.value;
                var crmid = $("#contactid").val(); 

                console.log(action);

                $.ajax(url, {
                    type: "POST",
                    data: data + "&action=" + action + "&crmid=" + crmid,
                    success: function (data) {
                        var result = jQuery.parseJSON(data);
                        console.log(result);
                        if (result["Type"] == "S") {
                            //console.log("Yes!");
                            swal({
                                position: "center", //'top-end',
                                type: "success",
                                title: result["Message"],
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            if(action == 'add'){
                              var data_crm = result['data'];
                              console.log(data_crm);
                              console.log(result['cache_time']);

                              $('#dateupdate_contact').val(moment(result['cache_time']).format('DD/MM/YYYY H:mm:ss'));

                              $('#contactid').val(data_crm['Crmid']);
                              $('#contact_code').val(data_crm['DocNo']);
                              //Bill to Infomation
                              $('#accountid').val(data_crm['CrmidAcc']);
                              $('#companyname').val(data_crm['companyname']);
                              $('#contact_person').val(data_crm['contact_person']);
                              $('#contact_tel').val(data_crm['contact_tel']);

                              //Add popup Searching
                              var firstname = $('#fristname_contact').val();
                              var phone_contact = $('#phone_contact').val();
                              var lineid_contact = $('#lineid_contact').val();
                              var facebook_contact = $('#facebook_contact').val();
                              $('#contactcode_searching').val(data_crm['DocNo']);
                              $('#contactname_searching').val(firstname);
                              $('#contacttel_searching').val(phone_contact);
                              $('#lineid_searching').val(lineid_contact);
                              $('#facebook_searching').val(facebook_contact);
                              //Add order
                              $('#contact_name').val(firstname);
                              $('#contact_no_order').val(data_crm['DocNo']);
                              $('#telephone_order').val(phone_contact);
                              $('#account_name').val(data_crm['companyname']);
                              $('#contact_person_order').val(data_crm['contact_person']);
                              $('#contact_tel_order').val(data_crm['contact_tel']);

                            }
                            
                            
                        } else {
                            console.log("No!");
                            swal("", result["Message"], "error");
                        }
                    },
                    error: function (data) {
                        console.log("error");
                    },
                });
            }

            // if (!fristname_contact.checkValidity() || !phone_contact.checkValidity()) {

            //     // document.getElementById("firstname_required").innerHTML = fristname_contact.validationMessage;
            //     // var firstname_required = document.getElementById('firstname_required');
            //     // firstname_required.textContent = fristname_contact.validationMessage;
            //     $("#firstname_required").text('* โปรดกรอกฟิลด์นี้');
            //     $("#phone_required").text('* โปรดกรอกฟิลด์นี้')
            //     // document.getElementById("phone_required").innerHTML = phone_contact.validationMessage;
            //     // console.log(fristname_contact.validationMessage);
            //     // console.log(phone_contact.validationMessage);
            // } else {
            //     console.log('t');
            //     // document.getElementById("firstname_required").innerHTML = "";
            //     // document.getElementById("phone_required").innerHTML = "";
            //     $("#firstname_required").text('');
            //     $("#phone_required").text('');
            //     // if (contactid == "") {
            //     // actiontext.value = "add";
            //     // console.log(actiontext.value);
            //     // } else {
            //     //     actiontext.value = "edit";
            //     //     console.log(actiontext.value);
            //     // }

            //     // var action = actiontext.value;
            //     // var crmid = $("#contactid").val(); 

            //     // console.log(action);

            //     // $.ajax(url, {
            //     //     type: "POST",
            //     //     data: data + "&action=" + action + "&crmid=" + crmid,
            //     //     success: function (data) {
            //     //         var result = jQuery.parseJSON(data);
            //     //         console.log(result);
            //     //         if (result["Type"] == "S") {
            //     //             //console.log("Yes!");
            //     //             swal({
            //     //                 position: "center", //'top-end',
            //     //                 type: "success",
            //     //                 title: result["Message"],
            //     //                 showConfirmButton: false,
            //     //                 timer: 2000,
            //     //             });

            //     //             if(action == 'add'){
            //     //               var data_crm = result['data'];
            //     //               console.log(data_crm);
            //     //               console.log(result['cache_time']);

            //     //               $('#dateupdate_contact').val(moment(result['cache_time']).format('DD/MM/YYYY H:mm:ss'));

            //     //               $('#contactid').val(data_crm['Crmid']);
            //     //               $('#contact_code').val(data_crm['DocNo']);
            //     //               //Bill to Infomation
            //     //               $('#accountid').val(data_crm['CrmidAcc']);
            //     //               $('#companyname').val(data_crm['companyname']);
            //     //               $('#contact_person').val(data_crm['contact_person']);
            //     //               $('#contact_tel').val(data_crm['contact_tel']);

            //     //               //Add popup Searching
            //     //               var firstname = $('#fristname_contact').val();
            //     //               var phone_contact = $('#phone_contact').val();
            //     //               var lineid_contact = $('#lineid_contact').val();
            //     //               var facebook_contact = $('#facebook_contact').val();
            //     //               $('#contactcode_searching').val(data_crm['DocNo']);
            //     //               $('#contactname_searching').val(firstname);
            //     //               $('#contacttel_searching').val(phone_contact);
            //     //               $('#lineid_searching').val(lineid_contact);
            //     //               $('#facebook_searching').val(facebook_contact);
            //     //               //Add order
            //     //               $('#contact_name').val(firstname);
            //     //               $('#contact_no_order').val(data_crm['DocNo']);
            //     //               $('#telephone_order').val(phone_contact);
            //     //               $('#account_name').val(data_crm['companyname']);
            //     //               $('#contact_person_order').val(data_crm['contact_person']);
            //     //               $('#contact_tel_order').val(data_crm['contact_tel']);

            //     //             }
                            
                            
            //     //         } else {
            //     //             console.log("No!");
            //     //             swal("", result["Message"], "error");
            //     //         }
            //     //     },
            //     //     error: function (data) {
            //     //         console.log("error");
            //     //     },
            //     // });

            // }
            // } else if (!phone_contact.checkValidity()) {
            //     console.log(phone_contact.validationMessage);
            // } else {
            //     console.log('t');
            // }

            // if (contactid == "") {
            //     actiontext.value = "add";
            //     console.log(actiontext.value);
            // } else {
            //     actiontext.value = "edit";
            //     console.log(actiontext.value);
            // }

            // var action = actiontext.value;
            // var crmid = $("#contactid").val(); 

            // console.log(action);

            // $.ajax(url, {
            //     type: "POST",
            //     data: data + "&action=" + action + "&crmid=" + crmid,
            //     success: function (data) {
            //         var result = jQuery.parseJSON(data);
            //         console.log(result);
            //         if (result["Type"] == "S") {
            //             //console.log("Yes!");
            //             swal({
            //                 position: "center", //'top-end',
            //                 type: "success",
            //                 title: result["Message"],
            //                 showConfirmButton: false,
            //                 timer: 2000,
            //             });

            //             if(action == 'add'){
            //               var data_crm = result['data'];
            //               console.log(data_crm);
            //               console.log(result['cache_time']);

                            $('#contactid').val(data_crm['Crmid']);
                            $('#contact_code').val(data_crm['DocNo']);
                             //Bill to Infomation
                            /*$('#accountid').val(data_crm['CrmidAcc']);
                            $('#companyname').val(data_crm['companyname']);
                            $('#contact_person').val(data_crm['contact_person']);
                            $('#contact_tel').val(data_crm['contact_tel']);*/

            //               $('#dateupdate_contact').val(moment(result['cache_time']).format('DD/MM/YYYY H:mm:ss'));


            //               $('#contactid').val(data_crm['Crmid']);
            //               $('#contact_code').val(data_crm['DocNo']);
            //               //Bill to Infomation
            //               $('#accountid').val(data_crm['CrmidAcc']);
            //               $('#companyname').val(data_crm['companyname']);
            //               $('#contact_person').val(data_crm['contact_person']);
            //               $('#contact_tel').val(data_crm['contact_tel']);

            //               //Add popup Searching
            //               var firstname = $('#fristname_contact').val();
            //               var phone_contact = $('#phone_contact').val();
            //               var lineid_contact = $('#lineid_contact').val();
            //               var facebook_contact = $('#facebook_contact').val();
            //               $('#contactcode_searching').val(data_crm['DocNo']);
            //               $('#contactname_searching').val(firstname);
            //               $('#contacttel_searching').val(phone_contact);
            //               $('#lineid_searching').val(lineid_contact);
            //               $('#facebook_searching').val(facebook_contact);
            //               //Add order
            //               $('#contact_name').val(firstname);
            //               $('#contact_no_order').val(data_crm['DocNo']);
            //               $('#telephone_order').val(phone_contact);
            //               $('#account_name').val(data_crm['companyname']);
            //               $('#contact_person_order').val(data_crm['contact_person']);
            //               $('#contact_tel_order').val(data_crm['contact_tel']);

            //             }
                        
                        
            //         } else {
            //             console.log("No!");
            //             swal("", result["Message"], "error");
            //         }
            //     },
            //     error: function (data) {
            //         console.log("error");
            //     },
            // });

        };

        $.saveOrder = function () { 
            // console.log("T");

            var data = $("#form_save_order").serialize();
            var url = "<?php echo site_url('home/create_order'); ?>";

            var accountid = $("#accountid").val();
            var contactid = $("#contactid").val();

            $.ajax(url, {
                type: "POST",
                data: data + "&accountid=" + accountid + "&contactid=" + contactid,
                success: function (data) {
                    var result = jQuery.parseJSON(data);
                    console.log(result);
                    if (result["Type"] == "S") {
                        swal({
                            position: "center", //'top-end',
                            type: "success",
                            title: result["Message"],
                            showConfirmButton: false,
                            timer: 2000,
                        });

                        $("#order_no").val("");
                        $("#order_status_order").val("Open").trigger("change");
                        $("#completed_sub_status_order").val("No Detail").trigger("change");
                        $("#completed_remark").val("");
                        $("#lost_reason_order").val("--None--").trigger("change");
                        $("#account_name").val("");
                        $("#contact_name").val("");
                        $("#address_order").val("");
                        $("#contact_no_order").val("");
                        $("#telephone_order").val("");
                        $("#sales_name").val("");
                        $("#objective_order").val("").trigger("change");
                        $("#sales_tel").val("");
                        $("#mix_easy_site_code").val("");
                        $("#assigned_user_id").val("");
                        $("#vendor_site_code").val("");
                        $("#delivery_location").val("");
                        $("#site_person").val("");
                        $("#site_phone_delivery").val("");
                        $("#fax_delivery").val("");
                        $("#province_order").val("");
                        $("#billing_name").val("");
                        $("#vendor_hq").val("");
                        $("#tax_address").val("");
                        $("#mailing_address").val("");
                        $("#taxpayer_identification_no_bill_to").val("");
                        $("#corporate_registration_number_crn").val("");
                        $("#phone_bill_to").val("");
                        $("#fax_bill_to").val("");
                        $("#contact_person_order").val("");
                        $("#contact_tel_order").val("");
                        $("#validity").val("");
                        $("#term_and_condition").val("");
                        $("#plan_date").val("");
                        $("#plan_time").val("");
                        $("#payment_method_name").val("");
                        $("#bank_order").val("").trigger("change");
                        $("#branch").val("");
                        $("#acc_no").val("");
                        $("#payment_method").val("Fund Transfer").trigger("change");
                        $("#receive_money").val("");
                        $("#not_match_payment_remark").val("");
                        $("#payment_remark").val("");
                        $("#vender_plant").val("");
                        $("#payment_code").val("");
                        $("#vendor_bank_order").val("").trigger("change");
                        $("#vendor_bank_account").val("");
                        $("#credit_term").val("");
                        $("#description_order").val("");
                        $("#productName1").val("");
                        $("#km").val("");
                        $("#zone").val("");
                        $("#carsize").val("");
                        $("#number1").val("0");
                        $("#number2").val("0");
                        $("#number3").val("0");
                        $("#number4").val("0");
                        $("#priceperunit1").val("");
                        $("#priceperunit2").val("0");
                        $("#priceperunit3").val("0");
                        $("#priceperunit4").val("0");
                        $("#amount1").val("");
                        $("#amount2").val("");
                        $("#amount3").val("");
                        $("#amount4").val("");
                        $("#discount").val("0");
                        $("#afterdiscount1").val("0");
                        $("#afterdiscount2").val("0");
                        $("#afterdiscount3").val("0");
                        $("#afterdiscount4").val("0");
                        $("#c_cost").val("");
                        $("#dlv_c").val("");
                        $("#min").val("");
                        $("#lp").val("")

                        var purchaseamount1show = document.getElementById('purchaseamount1show');
                        purchaseamount1show.textContent = "";

                        var purchaseamount2show = document.getElementById('purchaseamount2show');
                        purchaseamount2show.textContent = "";

                        var purchaseamount3show = document.getElementById('purchaseamount3show');
                        purchaseamount3show.textContent = "";

                        var purchaseamount4show = document.getElementById('purchaseamount4show');
                        purchaseamount4show.textContent = "";

                        var subTotal1show = document.getElementById('subTotal1show');
                        subTotal1show.textContent = "";

                        var vat1show = document.getElementById('Vat1show');
                        vat1show.textContent = "";

                        var total1show = document.getElementById('Total1show');
                        total1show.textContent = "";

                        var subTotal2show = document.getElementById('subTotal2show');
                        subTotal2show.textContent = "";

                        var vat2show = document.getElementById('Vat2show');
                        vat2show.textContent = "";

                        var total2show = document.getElementById('Total2show');
                        total2show.textContent = "";

                        $("#location_searching").val("");

                        var myModal = document.getElementById("myModal");
                        myModal.style.display = "none";

                        $('[href="#menu3"]').tab('show');

                        $.post(
                            site_url("home/gethistoryorder"),
                            { accountid: accountid, contactid: contactid },
                            function (rs) {
                                console.log(rs);
                                $("#grid_historyorder").data("kendoGrid").dataSource.data([]);
                                var grid_historyorder = $("#grid_historyorder").data("kendoGrid");
                                $.each(rs.data, function (key, value) {
                                    grid_historyorder.dataSource.add({
                                      orderno: value.order_no,
                                      order_status_order: value.order_status_order,
                                      project_address: value.project_address,
                                      order_status_order: value.order_status_order,
                                      plant_name: value.plant_name,
                                    });
                                    /*grid_import.dataSource.add({Accountname:value.Accountname,Adddatetime:value.Adddatetime,Address:value.Address,Agreementca:value.Agreementca,Agreementmeter:value.Agreementmeter,Updatedatetime:value.Updatedatetime});*/
                                });

                            },
                            "json"
                        );


                    } else {
                        swal("", result["Message"], "error");
                    }
                },
                error: function (data) {
                    console.log("error");
                },
            });

        };

        $.cancalOrder = function() {
            
            var myModal = document.getElementById("myModal");

            myModal.style.display = "none";

            var modalSearching = document.getElementById("modalSearching");
            modalSearching.style.display = "block";

            // $("#order_no").val("");
            // $("#order_status_order").val("Open").trigger("change");
            // $("#completed_sub_status_order").val("No Detail").trigger("change");
            // $("#completed_remark").val("");
            // $("#lost_reason_order").val("--None--").trigger("change");
            // $("#account_name").val("");
            // $("#contact_name").val("");
            // $("#address_order").val("");
            // $("#contact_no_order").val("");
            // $("#telephone_order").val("");
            // $("#sales_name").val("");
            // $("#objective_order").val("").trigger("change");
            // $("#sales_tel").val("");
            // $("#mix_easy_site_code").val("");
            // $("#assigned_user_id").val("");
            // $("#vendor_site_code").val("");
            // $("#delivery_location").val("");
            // $("#site_person").val("");
            // $("#site_phone_delivery").val("");
            // $("#fax_delivery").val("");
            // $("#province_order").val("");
            // $("#billing_name").val("");
            // $("#vendor_hq").val("");
            // $("#tax_address").val("");
            // $("#mailing_address").val("");
            // $("#taxpayer_identification_no_bill_to").val("");
            // $("#corporate_registration_number_crn").val("");
            // $("#phone_bill_to").val("");
            // $("#fax_bill_to").val("");
            // $("#contact_person_order").val("");
            // $("#contact_tel_order").val("");
            // $("#validity").val("");
            // $("#term_and_condition").val("");
            // $("#plan_date").val("");
            // $("#plan_time").val("");
            // $("#payment_method_name").val("");
            // $("#bank_order").val("").trigger("change");
            // $("#branch").val("");
            // $("#acc_no").val("");
            // $("#payment_method").val("Fund Transfer").trigger("change");
            // $("#receive_money").val("");
            // $("#not_match_payment_remark").val("");
            // $("#payment_remark").val("");
            // $("#vender_plant").val("");
            // $("#payment_code").val("");
            // $("#vendor_bank_order").val("").trigger("change");
            // $("#vendor_bank_account").val("");
            // $("#credit_term").val("");
            // $("#description_order").val("");
            $("#productName1").val("");
            $("#km").val("");
            $("#zone").val("");
            $("#carsize").val("");
            $("#number1").val("0");
            $("#number2").val("0");
            $("#number3").val("0");
            $("#number4").val("0");
            $("#priceperunit1").val("");
            $("#priceperunit2").val("0");
            $("#priceperunit3").val("0");
            $("#priceperunit4").val("0");
            $("#amount1").val("");
            $("#amount2").val("");
            $("#amount3").val("");
            $("#amount4").val("");
            $("#discount").val("0");
            $("#afterdiscount1").val("0");
            $("#afterdiscount2").val("0");
            $("#afterdiscount3").val("0");
            $("#afterdiscount4").val("0");
            $("#c_cost").val("");
            $("#dlv_c").val("");
            $("#min").val("");
            $("#lp").val("")

            var purchaseamount1show = document.getElementById('purchaseamount1show');
            purchaseamount1show.textContent = "";

            var purchaseamount2show = document.getElementById('purchaseamount2show');
            purchaseamount2show.textContent = "";

            var purchaseamount3show = document.getElementById('purchaseamount3show');
            purchaseamount3show.textContent = "";

            var purchaseamount4show = document.getElementById('purchaseamount4show');
            purchaseamount4show.textContent = "";

            var subTotal1show = document.getElementById('subTotal1show');
            subTotal1show.textContent = "";

            var vat1show = document.getElementById('Vat1show');
            vat1show.textContent = "";

            var total1show = document.getElementById('Total1show');
            total1show.textContent = "";

            var subTotal2show = document.getElementById('subTotal2show');
            subTotal2show.textContent = "";

            var vat2show = document.getElementById('Vat2show');
            vat2show.textContent = "";

            var total2show = document.getElementById('Total2show');
            total2show.textContent = "";

            $("#location_searching").val("");

        }

        // var saveOrder = document.getElementById("saveOrder");

        // saveOrder.onclick = function () {

        //     var data = $("#form_save_order").serialize();
        //     var url = "<?php echo site_url('home/create_order'); ?>";

        //     $.ajax(url, {
        //         type: "POST",
        //         data: data,
        //         success: function (data) {
        //             var result = jQuery.parseJSON(data);
        //             console.log(result);
        //             if (result["Type"] == "S") {
        //                 console.log("Save Success!");

        //             } else {
        //                 console.log("Save No!");

        //             }
        //         },
        //         error: function (data) {
        //             console.log("error");
        //         },
        //     });

            
        // };

        // Clock pickers
       // $('#single-input').clockpicker({
            //placement: 'bottom',
            //align: 'left',
            //autoclose: true,
            //'default': 'now'
        //});
        
        var startTime = document.getElementById("startTime");
        var plan_time = document.getElementById("plan_time");

        startTime.addEventListener("input", function() {
          plan_time.value = startTime.value;
        }, false);
        $.emailKm = function () {
            console.log("tt");

            var modalEmailKm = document.getElementById("modalEmailKm");
            var closeemailkm = document.getElementById("closeemailkm");

            modalEmailKm.style.display = "block";

            closeemailkm.onclick = function () {
                modalEmailKm.style.display = "none";
            };

            // window.onclick = function(event) {
            //     if (event.target == modalEmailKm) {
            //         modalEmailKm.style.display = "none";
            //     }
            // }
        };
    });

    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    // CKEDITOR.replace("detail");
    // function CKupdate() {
    //     for (instance in CKEDITOR.instances) CKEDITOR.instances[instance].updateElement();
    // }

    function compare(a, b) {
      // Use toUpperCase() to ignore character casing
      const kmA = a[5];
      const kmB = b[5];
      console.log(kmA);
      console.log(kmB);
      let comparison = 0;
      if (kmA > kmB) {
        comparison = 1;
      } else if (kmA < kmB) {
        comparison = -1;
      }
      return comparison;
    }
</script>

<script>

    //  var validobj;
    
    // $(document).ready(function() {
    //     //Transforms the listbox visually into a Select2.
    //     $("#task_name").select2({
    //         placeholder: "",
    //     });
    //     //Initialize the validation object which will be called on form submit.

    //     validobj = $("#form_save_case").validate({
    //         errorClass: "myErrorClass",
    //         rules:{
    //            lstColors: "required"
    //        },
    //        messages:{
    //            lstColors: "select2 is required"
    //        }
    //     });

    // });

    // var validobj = $("#form_save_case").validate({
    //     errorClass: "myErrorClass",
    //     rules:{
    //      task_name: "required"
    //  },
    //  messages:{
    //      task_name: "select2 is required"
    //  }

    // });

    $("#task_name").select2();

    var taskname = $("#task_name").val();
    // console.log(taskname);

    $('#descrip_case').val(taskname);

    function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the link that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    var modalAccountsearch = document.getElementById("modalAccountsearch");

    var btnadsearch = document.getElementById("adsearch");

    var closeAccountsearch = document.getElementById("closeAccountsearch");

    btnadsearch.onclick = function () {
        //Clear Session Grid
        var grid = $("#grid").data("kendoGrid");
        grid.select("tr:eq(1)");
        grid.clearSelection();
        modalAccountsearch.style.display = "block";
    };

    closeAccountsearch.onclick = function () {
        modalAccountsearch.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target == modalAccountsearch) {
            modalAccountsearch.style.display = "none";
        }
    };

    // var modalSearching = document.getElementById("modalSearching");

    // var btnsearching = document.getElementById("searching");

    // var closesearching = document.getElementById("closesearching");

    // btnadsearch.onclick = function() {
    //     modalSearching.style.display = "block"
    // }

    // Get the modal
    var modal = document.getElementById("modalSearching");

    // Get the button that opens the modal
    var btn = document.getElementById("searching");

    // Get the <span> element that closes the modal
    // var span = document.getElementsByClassName("close")[0];
    var closesearching = document.getElementById("closesearching");

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // When the user clicks the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";

        var url = "<?php echo site_url('home/getplant'); ?>";

        $.ajax(url, {
            type: "POST",
            data: "",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    console.log("success");
                } else {
                    console.log("fall");
                }
            },
            error: function (data) {
                console.log("f");
            },
        });

        var datasourcevippartner = {
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

        var columnsvippartner = [
            // { template: "<input type='checkbox' class='checkbox' />" },
            { selectable: true, width: "50px", headerTemplate: "เลือก" },
            {
                field: "plant_name",
                title: "แพล้นท์",
                width: 100,
            },
            {
                field: "km",
                title: "ห่าง",
                width: 70,
            },
            {
                field: "mat_type",
                title: "ประเภท",
                width: 70,
            },
            {
                field: "strength",
                title: "ST(Cube)",
                width: 65,
            },
            {
                field: "profit",
                title: "กำไร",
                width: 100,
            },
            {
                field: "c_price_vat",
                title: "ขาย",
                attributes:{
                  "class" : "color-red"
                },
                width: 100,
            },
            {
                field: "min",
                title: "Min",
                width: 100,
            },
            {
                field: "dlv_c_vat",
                title: "ค่าส่ง",
                width: 100,
            },
            {
                field: "truck_size",
                title: "ขนาดรถ",
                width: 100,
            },
            {
                field: "lp",
                title: "LP",
                width: 100,
            },
            {
                field: "lp_disc",
                title: "ลด(%)",
                width: 100,
            },
            {
                field: "c_cost_vat",
                title: "ทุน + VAT",
                width: 100,
            },
            {

                field: "productcode",
                title: "รหัส",
                width: 100,
            },
            {
                field: "into",
                title: "into",
                template: "<i class='fa fa-info-circle k-grid-into'></i>",
                width: 50,
            },
        ];

        $("#grid_vippartner").genKendoGridVipPartner(datasourcevippartner, columnsvippartner);

        // $("#grid_vippartner").kendoTooltip({
        //       filter: "td:nth-child(2)", //this filter selects the second column's cells
        //       position: "right",
        //       content: function(e){
        //         var dataItem = $("#grid").data("kendoGrid").dataItem(e.target.closest("tr"));
        //         var content = dataItem.Text;
        //         return content;
        //     }
        // }).data("kendoTooltip");

        // var formDatakm = $('#grid_vippricelist').serialize();
        var urlvippartner = "<?php echo site_url('home/getpricelist'); ?>";

        $.ajax(urlvippartner, {
            type: "POST",
            data: "",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    // $("#grid_vippartner").data("kendoGrid").dataSource.data([]);
                    // var gridvippartner = $("#grid_vippartner").data("kendoGrid");
                    // $.each(result.data, function (key, value) {
                    //     gridvippartner.dataSource.add({
                    //         plant_name: value.plant_name,
                    //         zone: value.zone,
                    //         mat_type: value.mat_type,
                    //         strength: value.strength,
                    //         c_cost: value.c_cost,
                    //         dlv_c: value.dlv_c,
                    //         pricelist_no: value.pricelist_no,
                    //         truck_size: value.truck_size,
                    //         lp: value.lp,
                    //         dlv_p_vat: value.dlv_p_vat,
                    //         c_price_vat: value.c_price_vat,
                    //         vendor_product_code: value.vendor_product_code,
                    //         remark: value.remark,
                    //         plantid: value.plantid,
                    //         dlv_c_vat: value.dlv_c_vat,
                    //         min: value.min,
                    //     });
                    // });

                    // $("#grid_vippartner")
                    //     .kendoTooltip({
                    //         filter: ".k-grid-into",
                    //         position: "left",
                    //         // showOn: "click",
                    //         content: function (e) {
                    //             var dataItem = $("#grid_vippartner").data("kendoGrid").dataItem(e.target.closest("tr"));
                    //             return (
                    //                 '<div><span style="color: #2B2B2B; margin-top: 10px; font-family: PromptMedium; font-weight: 700; font-size: 12px;"> ' +
                    //                 dataItem.plant_name +
                    //                 ' </span><hr><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">ติดต่อ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">เซลล์:</span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">บริษัท: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">จังหวัด: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">ไชลินเดอร์: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">ค่ายุบตัว: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">โซน: ' +
                    //                 dataItem.zone +
                    //                 ' </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">วันที่ทำการ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">เวลาทำการ: </span></div>'
                    //             );
                    //         },
                    //     })
                    //     .data("kendoTooltip");

                    // $("#grid_vippartner")
                    //     .kendoTooltip({
                    //         filter: "td:nth-child(2)", //this filter selects the second column's cells
                    //         position: "right",
                    //         callout: false,
                    //         content: function (e) {
                    //             var dataItem = $("#grid_vippartner").data("kendoGrid").dataItem(e.target.closest("tr"));
                    //             return '<div><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 600; font-size: 11px;">วันที่ทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">จันทร์ - ศุกร์</span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 600; font-size: 11px;">เวลาทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;"> 09.00 - 18.00</span></div>';
                    //         },
                    //     })
                    //     .data("kendoTooltip");
                } else {
                    $("#grid_vippartner").data("kendoGrid").dataSource.data([]);
                }
            },
            error: function (data) {
                console.log("f");
            },
        });

        var datasourcepartner = {
            pageSize: 10,
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

        var columnspartner = [
            // { template: "<input type='checkbox' class='checkbox' />" },
            { selectable: true, width: "50px", headerTemplate: "เลือก" },
            {
                field: "plant_name",
                title: "แพล้นท์",
                width: 100,
            },
            {
                field: "km",
                title: "ห่าง",
                width: 70,
            },
            {
                field: "mat_type",
                title: "ประเภท",
                width: 70,
            },
            {
                field: "strength",
                title: "ST(Cube)",
                width: 65,
            },
            {
                field: "profit",
                title: "กำไร",
                width: 100,
            },
            {
                field: "c_price_vat",
                title: "ขาย",
                attributes:{
                  "class" : "color-red"
                },
                width: 100,
            },
            {
                field: "min",
                title: "Min",
                width: 100,
            },
            {
                field: "dlv_c_vat",
                title: "ค่าส่ง",
                width: 100,
            },
            {
                field: "truck_size",
                title: "ขนาดรถ",
                width: 100,
            },
            {
                field: "lp",
                title: "LP",
                width: 100,
            },
            {
                field: "lp_disc",
                title: "ลด(%)",
                width: 100,
            },
            {
                field: "c_cost_vat",
                title: "ทุน + VAT",
                width: 100,
            },
            {
                field: "productcode",
                title: "รหัส",
                width: 100,
            },
            {
                field: "into",
                title: "into",
                template: "<i class='fa fa-info-circle k-grid-into'></i>",
                width: 50,
            },
        ];

        $("#grid_partner").genKendoGridPartner(datasourcepartner, columnspartner);

        var urlpartner = "<?php echo site_url('home/getpricelist'); ?>";

        $.ajax(urlpartner, {
            type: "POST",
            data: "",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    // $("#grid_partner").data("kendoGrid").dataSource.data([]);
                    // var gridpartner = $("#grid_partner").data("kendoGrid");
                    // $.each(result.data, function (key, value) {
                    //     gridpartner.dataSource.add({
                    //         plant_name: value.plant_name,
                    //         zone: value.zone,
                    //         mat_type: value.mat_type,
                    //         strength: value.strength,
                    //         c_cost: value.c_cost,
                    //         dlv_c: value.dlv_c,
                    //         pricelist_no: value.pricelist_no,
                    //         truck_size: value.truck_size,
                    //         lp: value.lp,
                    //         dlv_p_vat: value.dlv_p_vat,
                    //         c_price_vat: value.c_price_vat,
                    //         vendor_product_code: value.vendor_product_code,
                    //         remark: value.remark,
                    //     });
                    // });

                    // $("#grid_partner")
                    //     .kendoTooltip({
                    //         filter: ".k-grid-into",
                    //         position: "left",
                    //         content: function (e) {
                    //             var dataItem = $("#grid_partner").data("kendoGrid").dataItem(e.target.closest("tr"));
                    //             return (
                    //                 '<div><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 12px; font-weight: 700;">' +
                    //                 dataItem.plant_name +
                    //                 '</span><hr><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">ติดต่อ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">เซลล์: </span><br><span style="color: #2B2B2B; font-family:PromptMedium; font-size: 11px; font-weight: 500;">บริษัท: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">จังหวัด: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">ไชลินเดอร์: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">ค่ายุบตัว: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">โซน: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">วันที่ทำการ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">เวลาทำการ: </span></div>'
                    //             );
                    //         },
                    //     })
                    //     .data("kendoTooltip");

                    // $("#grid_partner")
                    //     .kendoTooltip({
                    //         filter: "td:nth-child(2)", //this filter selects the second column's cells
                    //         position: "right",
                    //         callout: false,
                    //         content: function (e) {
                    //             var dataItem = $("#grid_partner").data("kendoGrid").dataItem(e.target.closest("tr"));
                    //             return '<div><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 600;">วันที่ทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;"> จันทร์ - ศุกร์</span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 600;">เวลาทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">09:00 - 18:00</span></div>';
                    //         },
                    //     })
                    //     .data("kendoTooltip");
                } else {
                    $("#grid_partner").data("kendoGrid").dataSource.data([]);
                }
            },
            error: function (data) {
                console.log("f");
            },
        });
    };

    // When the user clicks on <span> (x), close the modal
    closesearching.onclick = function () {
        modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    var myModal = document.getElementById("myModal");

    var savesearchingbtn = document.getElementById("savesearchingbtn");

    var closeordermanagement = document.getElementById("closeordermanagement");

    // $("#usage_date").on("change", function() {
    //     this.setAttribute(
    //         "data-date",
    //         moment(this.value, "YYYY-MM-DD HH:mm")
    //         .format( this.getAttribute("data-date-format") )
    //         )
    // }).trigger("change");

    savesearchingbtn.onclick = function () {
        modal.style.display = "none";
        if (modal.style.display == "none") {
            myModal.style.display = "block";
        }

        var objectiveselected = document.getElementById("objective");
        var objectivetext = objectiveselected.options[objectiveselected.selectedIndex].text;
        $("#objective_order").val(objectivetext).trigger('change');

        var projectaddress_searching = document.getElementById('projectaddress_searching');
        var trucksize = document.getElementById('trucksize');
        var mattype = document.getElementById('mattype');
        var descrtion_searching = document.getElementById('descrtion_searching');

        $("#project_addressorder").val(projectaddress_searching.value);
        $("#truck_size_order").val(trucksize.value).trigger('change');
        $("#mat_type_order").val(mattype.value).trigger('change');
        $("#descrtion_order").val(descrtion_searching.value);

        console.log($("#project_addressorder").val());
        // var locationsearching = $('#location_searching').val();
        // console.log(locationsearching);
        var locationsearching = document.getElementById('location_searching');
        console.log(locationsearching.value);
        $('#delivery_location_order').val(locationsearching.value);

        var usage_date = document.getElementById('usage_date');
        var usagedate = usage_date.value.replace("T"," ");
        var plantdate = usagedate.substring(0, 10);
        var planttime = usagedate.substring(11,17);
       /* console.log(usagedate);
        console.log(plantdate);
        console.log(planttime);*/

        var date= plantdate;
        console.log(date);
        if (date != "") {
            var d = new Date(date.split("/").reverse().join("-"));
            var dd=d.getDate();
             //var mm=d.getMonth()+1;
             var mm = ("0" + (d.getMonth() + 1)).slice(-2);
             var yy=d.getFullYear();
            // alert(dd+"-"+mm+"-"+yy);
            var plantdateformat = dd+"/"+mm+"/"+yy;
            console.log(plantdateformat);
            $("#plan_date").val(plantdateformat);
            
        }
        
        $("#startTime").val(planttime);
        $("#plan_time").val(planttime);

        // var order_date = document.getElementById('order_date');
        // console.log(order_date.value);
        // var orderdate = order_date.value.replace("/","-");
        // console.log(orderdate);

        $('#order_date')[0].valueAsDate = new Date();

        $('#order_date').change(function() {
          var date= this.valueAsDate;
          date.setDate(date.getDate() + 14);
          $('#validity')[0].valueAsDate = date;
      });

        $('#order_date').change();

        var queueqty = document.getElementById("queueqty");

        $("#queue_qty").val(queueqty.value);
        $('#number1').val(queueqty.value);

        var number1 = document.getElementById('number1');
        console.log(number1.value);

        var priceperunit1 = document.getElementById('priceperunit1');

        var price = parseInt(priceperunit1.value);
        var tot_price = number1.value * price;
        var total = tot_price;
        $("#amount1").val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

        var afterdiscount1 = document.getElementById("afterdiscount1");
        afterdiscount1.value;

        totalproduct = number1.value * afterdiscount1.value;
        $('#purchaseamount1').val(totalproduct);
        var purchaseamount1show = document.getElementById('purchaseamount1show');
        purchaseamount1show.textContent = totalproduct.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

        var number2 = document.getElementById('number2');
        var priceperunit2 = document.getElementById('priceperunit2');
            
        var sumnumber2 = number2.value * priceperunit2.value;
        var total2 = sumnumber2;
        $("#amount2").val(total2.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

        var number3 =  document.getElementById('number3');
        var priceperunit3 = document.getElementById('priceperunit3');
        // console.log(number3.value);
        // var pricenumber3 = 12900;
        var sumnumber3 = number3.value * priceperunit3.value;
        var total3 = sumnumber3;
        $("#amount3").val(total3.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

        var number4 = document.getElementById('number4');
        var priceperunit4 = document.getElementById('priceperunit4');
        // console.log(number4.value);
        // var pricenumber4 = 107;
        var sumnumber4 = number4.value * priceperunit4.value;
        var total4 = sumnumber4;
        $("#amount4").val(total4.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

        var sumtotal1 = total + total2 + total3 + total4;
        var vatsumtotal1 = (sumtotal1 * 7)/107;
        var total1sub =  sumtotal1 + vatsumtotal1;

        var total1sum = document.getElementById('Total1show');
        total1sum.textContent = total1sub.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $("#Total1").val(total1sub.toFixed(2));
        // console.log(sumtotal1);


        var vat1 = document.getElementById('Vat1show');
        vat1.textContent = vatsumtotal1.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $("#Vat1").val(vatsumtotal1.toFixed(2));
        // console.log(vatsumtotal1);

        var subTotal1show = document.getElementById('subTotal1show');
        subTotal1show.textContent = sumtotal1.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $("#subTotal1").val(sumtotal1.toFixed(2));

        var afterdiscount2 = document.getElementById('afterdiscount2');
        // console.log(afterdiscount2.value);
        var purchaseamount2show = document.getElementById('purchaseamount2show');
        purchaseamount2show.textContent = parseInt(afterdiscount2.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $('#purchaseamount2').val(parseInt(afterdiscount2.value));

        var afterdiscount3 = document.getElementById('afterdiscount3');

        var purchaseamount3show = document.getElementById('purchaseamount3show');
        purchaseamount3show.textContent = parseInt(afterdiscount3.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

        $('#purchaseamount3').val(parseInt(afterdiscount3.value).toFixed(2));

        var afterdiscount4 = document.getElementById('afterdiscount4');
        var purchaseamount4show = document.getElementById('purchaseamount4show');
        purchaseamount4show.textContent = parseInt(afterdiscount4.value).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');

        $('#purchaseamount4').val(parseInt(afterdiscount4.value));

        var purchaseamount1 = document.getElementById('purchaseamount1');
        // console.log(parseInt(purchaseamount1.value) || 0);
        var purchaseamount1sum = parseInt(purchaseamount1.value) || 0;
        var purchaseamount2 = document.getElementById('purchaseamount2');
        var purchaseamount2sum = parseInt(purchaseamount2.value) || 0;
        // console.log(parseInt(purchaseamount2.value) || 0);
        var purchaseamount3 = document.getElementById('purchaseamount3');
        var purchaseamount3sum = parseInt(purchaseamount3.value) || 0;
        var purchaseamount4 = document.getElementById('purchaseamount4');
        var purchaseamount4sum = parseInt(purchaseamount4.value) || 0;

        var sumsubTotal2 = purchaseamount1sum + purchaseamount2sum + purchaseamount3sum + purchaseamount4sum;
        console.log(sumsubTotal2);
        var subTotal2show = document.getElementById('subTotal2show');
        subTotal2show.textContent = sumsubTotal2.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $("#subTotal2").val(sumsubTotal2.toFixed(2));

        var vatsumtotaldiscount = (sumsubTotal2 * 0.07);
        // console.log(vatsumtotaldiscount);
        var vat2show = document.getElementById('Vat2show');
        vat2show.textContent = vatsumtotaldiscount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $("#Vat2").val(vatsumtotaldiscount.toFixed(2));


        var sumtotal2 = sumsubTotal2 + vatsumtotaldiscount;
        var total2text = document.getElementById('Total2show');
        // console.log(sumtotaldiscount + vatsumtotaldiscount);
        total2text.textContent = sumtotal2.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        $("#Total2").val(sumtotal2.toFixed(2));


        // var today = moment();
        // var tomorrow = moment(today).add(15, 'days');

        // console.log(tomorrow);

        // var ccost = c_cost.replace(",","");

        // 2020-12-18 17:29

        // console.log(strUser);

        // $('#myModal .modal-body').html('');

        // var url = "<?php echo site_url('home/getoder'); ?>";

        // console.log(formData);
        // $.ajax(url, {
        //     type: "POST",
        //     data: "",
        //     success: function (data) {
        //         var result = jQuery.parseJSON(data);
        //         console.log(result.data);
        //         if (result["Type"] == "S") {
        //             console.log("S");
        //         } else {
        //             console("E");
        //         }
        //     },
        //     error: function (data) {
        //         console.log("f");
        //     },
        // });
    };

    closeordermanagement.onclick = function () {
        myModal.style.display = "none";

        // $("#order_no").val("");
        // $("#order_status_order").val("Open").trigger("change");
        // $("#completed_sub_status_order").val("No Detail").trigger("change");
        // $("#completed_remark").val("");
        // $("#lost_reason_order").val("--None--").trigger("change");
        // $("#account_name").val("");
        // $("#contact_name").val("");
        // $("#address_order").val("");
        // $("#contact_no_order").val("");
        // $("#telephone_order").val("");
        // $("#sales_name").val("");
        // $("#objective_order").val("").trigger("change");
        // $("#sales_tel").val("");
        // $("#mix_easy_site_code").val("");
        // $("#assigned_user_id").val("");
        // $("#vendor_site_code").val("");
        // $("#delivery_location").val("");
        // $("#site_person").val("");
        // $("#site_phone_delivery").val("");
        // $("#fax_delivery").val("");
        // $("#province_order").val("");
        // $("#billing_name").val("");
        // $("#vendor_hq").val("");
        // $("#tax_address").val("");
        // $("#mailing_address").val("");
        // $("#taxpayer_identification_no_bill_to").val("");
        // $("#corporate_registration_number_crn").val("");
        // $("#phone_bill_to").val("");
        // $("#fax_bill_to").val("");
        // $("#contact_person_order").val("");
        // $("#contact_tel_order").val("");
        // $("#validity").val("");
        // $("#term_and_condition").val("");
        // $("#plan_date").val("");
        // $("#plan_time").val("");
        // $("#payment_method_name").val("");
        // $("#bank_order").val("").trigger("change");
        // $("#branch").val("");
        // $("#acc_no").val("");
        // $("#payment_method").val("Fund Transfer").trigger("change");
        // $("#receive_money").val("");
        // $("#not_match_payment_remark").val("");
        // $("#payment_remark").val("");
        // $("#vender_plant").val("");
        // $("#payment_code").val("");
        // $("#vendor_bank_order").val("").trigger("change");
        // $("#vendor_bank_account").val("");
        // $("#credit_term").val("");
        // $("#description_order").val("");
        $("#productName1").val("");
        $("#km").val("");
        $("#zone").val("");
        $("#carsize").val("");
        $("#number1").val("0");
        $("#number2").val("0");
        $("#number3").val("0");
        $("#number4").val("0");
        $("#priceperunit1").val("");
        $("#priceperunit2").val("0");
        $("#priceperunit3").val("0");
        $("#priceperunit4").val("0");
        $("#amount1").val("");
        $("#amount2").val("");
        $("#amount3").val("");
        $("#amount4").val("");
        $("#discount").val("0");
        $("#afterdiscount1").val("0");
        $("#afterdiscount2").val("0");
        $("#afterdiscount3").val("0");
        $("#afterdiscount4").val("0");
        $("#c_cost").val("");
        $("#dlv_c").val("");
        $("#min").val("");
        $("#lp").val("")

        var purchaseamount1show = document.getElementById('purchaseamount1show');
        purchaseamount1show.textContent = "";

        var purchaseamount2show = document.getElementById('purchaseamount2show');
        purchaseamount2show.textContent = "";

        var purchaseamount3show = document.getElementById('purchaseamount3show');
        purchaseamount3show.textContent = "";

        var purchaseamount4show = document.getElementById('purchaseamount4show');
        purchaseamount4show.textContent = "";

        var subTotal1show = document.getElementById('subTotal1show');
        subTotal1show.textContent = "";

        var vat1show = document.getElementById('Vat1show');
        vat1show.textContent = "";

        var total1show = document.getElementById('Total1show');
        total1show.textContent = "";

        var subTotal2show = document.getElementById('subTotal2show');
        subTotal2show.textContent = "";

        var vat2show = document.getElementById('Vat2show');
        vat2show.textContent = "";

        var total2show = document.getElementById('Total2show');
        total2show.textContent = "";

        $("#location_searching").val("");
    };

    window.onclick = function (event) {
        if (event.target == myModal) {
            myModal.style.display = "none";
        }
    };

    //   function myMap() {
    //       var mapProp= {
    //         center:new google.maps.LatLng(51.508742,-0.120850),
    //         zoom:5,
    //     };
    //     var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    // }

    var map;

    function myMap() {
        //   var myLatLng = { lat: -25.363, lng: 131.044 };
        //   var map = new google.maps.Map(document.getElementById("googleMap"), {
        //     zoom: 4,
        //     center: myLatLng,
        // });
        //   new google.maps.Marker({
        //     position: myLatLng,
        //     map,
        //     title: "Hello World!",
        // });
        var myLatLng = { lat: 13.7237507, lng: 100.5183732 };
        // var myLatLng = { lat: -34.397, lng: 150.644 };
        map = new google.maps.Map(document.getElementById("googleMap"), {
            zoom: 10,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: true,
        });

        var geocoder = new google.maps.Geocoder();
        // new google.maps.Marker({
        //     position: myLatLng,
        //     map,
        //     title: "Hello World!",
        // });

        // Create the search box and link it to the UI element.
        var input = document.getElementById("location_searching");
        // console.log(input.value);
    
        var searchBox = new google.maps.places.SearchBox(input);
        // console.log(searchBox);
        // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
            // console.log(map.getBounds());
            // console.log($('#location_searching').val());
        });

        let markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", () => {
            var places = searchBox.getPlaces();
            // console.log(places);
            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            // console.log(bounds);
            places.forEach((place) => {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                // var icon = {
                //     url: place.icon,
                //     size: new google.maps.Size(71, 71),
                //     origin: new google.maps.Point(0, 0),
                //     anchor: new google.maps.Point(17, 34),
                //     scaledSize: new google.maps.Size(25, 25),
                // };
                // Create a marker for each place.
                markers.push(
                    new google.maps.Marker({
                        map,
                        // icon,
                        title: place.name,
                        position: place.geometry.location,
                    })
                );

                // console.log(place.name);
                console.log("latitude: " + place.geometry.location.lat() + ", longitude: " + place.geometry.location.lng());

                // var latitudeplant = place.geometry.location.lat();
                // console.log(latitudeplant);

                // var longitudeplant = place.geometry.location.lng();
                // console.log(longitudeplant);
                // console.log(place.adr_address);
                // console.log(place.formatted_address);
                // console.log(place.name);
                // console.log(place.address_components[3].long_name);

                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();

                $("#latitudeplant").val(place.geometry.location.lat());
                $("#longitudeplant").val(place.geometry.location.lng());

                var city2;

                if (navigator.geolocation) {

                    var LatLng = new google.maps.LatLng(latitude, longitude);
                    console.log(LatLng);

                    geocoder.geocode({
                        'location': LatLng
                      }, function(results, status) {
                        console.log("geocoder callback status=" + status);
                        if (status === 'OK') {
                          if (results[0]) {
                            // map.setZoom(11);
                            // from "Google maps API, get the users city/ nearest city/ general area"
                            // https://stackoverflow.com/questions/50081245/google-maps-api-get-the-users-city-nearest-city-general-area
                            var details = results[0].address_components;
                            var city;
                            var country;
                            console.log(JSON.stringify(details));
                            for (var i = details.length - 1; i >= 0; i--) {
                              for (var j = 0; j < details[i].types.length; j++) {
                                if (details[i].types[j] == 'locality') {
                                  city = details[i].long_name;
                                } else if (details[i].types[j] == 'sublocality') {
                                  city = details[i].long_name;
                                } else if (details[i].types[j] == 'neighborhood') {
                                  city = details[i].long_name;
                                } else if (details[i].types[j] == 'postal_town') {
                                  city = details[i].long_name;
                                  console.log("postal_town=" + city);
                                } else if (details[i].types[j] == 'administrative_area_level_1') {
                                  city2 = details[i].long_name;
                                  $("#cityplant").val(city2);
                                  $("#province_order").val(city2);
                                  console.log("admin_area_2=" + city2);
                                }
                                // from "google maps API geocoding get address components"
                                // https://stackoverflow.com/questions/50225907/google-maps-api-geocoding-get-address-components
                                if (details[i].types[j] == "country") {
                                  country = details[i].long_name;
                                }
                              }
                            }
                            console.log("city=" + city);
                            console.log("city2=" + city2);
                            console.log("country=" + country);
                            getplant();
                            // var marker = new google.maps.Marker({
                            //   position: LatLng,
                            //   map: map,
                            //   title: "<div style = 'height:80px;width:200px'><b>Your location:</b><br />Latitude: " + p.coords.latitude + "<br />Longitude: " + p.coords.longitude + "<br/>Country:" + country + "<br/>City:" + city
                            // });
                            // google.maps.event.addListener(marker, "click", function(e) {
                            //   var infoWindow = new google.maps.InfoWindow();
                            //   infoWindow.setContent(marker.title);
                            //   infoWindow.open(map, marker);
                            // });
                            // google.maps.event.trigger(marker, 'click');
                          } else {
                            window.alert('No results found');
                          }
                        } else {
                          window.alert('Geocoder failed due to: ' + status);
                        }
                      });

                } else {
                    alert('Geo Location feature is not supported in this browser.');
                }

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
            // console.log(bounds);
        });

        // var locations = [
        //     ["Bondi Beach", -33.890542, 151.274856, 4],
        //     ["Coogee Beach", -33.923036, 151.259052, 5],
        //     ["Cronulla Beach", -34.028249, 151.157507, 3],
        //     ["Manly Beach", -33.80010128657071, 151.28747820854187, 2],
        //     ["Maroubra Beach", -33.950198, 151.259302, 1],
        // ];

        // var userCoorPath = [
        //     new google.maps.LatLng(-33.890542, 151.274856),
        //     new google.maps.LatLng(-33.923036, 151.259052),
        //     new google.maps.LatLng(-34.028249, 151.157507),
        //     new google.maps.LatLng(-33.80010128657071, 151.28747820854187),
        //     new google.maps.LatLng(-33.950198, 151.259302),
        // ];

        // var userCoordinate = new google.maps.Polyline({
        //     path: userCoorPath,
        //     strokeColor: "#018FFB",
        //     strokeOpacity: 1,
        //     strokeWeight: 2,
        // });
        // userCoordinate.setMap(map);

        //     var icon = {
        //     url: "<?php echo site_assets_url('images/icons/searching.png'); ?>", // url
        //     scaledSize: new google.maps.Size(50, 50), // scaled size
        //     origin: new google.maps.Point(0,0), // origin
        //     anchor: new google.maps.Point(0, 0) // anchor
        // };

        // var infowindow = new google.maps.InfoWindow();

        // var marker, i;

        // for (i = 0; i < locations.length; i++) {
        //     marker = new google.maps.Marker({
        //         position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        //         map: map,
        //         icon: {
        //             url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
        //         },
        //         // icon: icon
        //     });

        //     google.maps.event.addListener(
        //         marker,
        //         "click",
        //         (function (marker, i) {
        //             return function () {
        //                 infowindow.setContent(locations[i][0]);
        //                 infowindow.open(map, marker);
        //             };
        //         })(marker, i)
        //     );
        // }
    }

    function getplant() {

        // console.log("t");
        var lat = $("#latitudeplant").val();
        // console.log(lat);
        var long = $("#longitudeplant").val();
        // console.log(long);
        var trucksizeplantvalue = document.getElementById("trucksize");
        var trucksizeplant = trucksizeplantvalue.value;
        // console.log(trucksizeplant);
        var mattypevalue = document.getElementById("mattype");
        var mattypeplant = mattypevalue.value;
        // console.log(mattypeplant);
        var strengthplant = $('#strength').val();
        // console.log(strengthplant);
        var cityplant = $("#cityplant").val();
        // console.log(cityplant);

        var data = {
            latitudeplant: lat,
            longitudeplant: long,
            trucksizeplant: trucksizeplant,
            mattypeplant: mattypeplant,
            strengthplant: strengthplant,
            cityplant: cityplant,
        };

        var urlgetplant = "<?php echo site_url('home/plan_get'); ?>";
        var data_plant = [];
        var locations = [];
        var mapkm = [];
        var poly = [];
        var locationmapkm = [];
        $.ajax(urlgetplant, {
            type: "POST",
            data: data,
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                if (result["Type"] == "S") {
                    // console.log("s");

                    $.each(result.data, function( index, value ) {

                        var kmplant = '';
                        var mylatitude = $('#latitudeplant').val();
                        var mylongitude = $('#longitudeplant').val();
                        
                        // var lattitude_value = $('#latitudeplant').val();
                        // var longitude_value = $('#longitudeplant').val();

                        var to = new google.maps.LatLng(mylatitude, mylongitude);
                        var from = new google.maps.LatLng(value.latitude, value.longtitude);
                        var directionsService = new google.maps.DirectionsService();
                        var directionsRequest = {
                            origin: from,
                            destination: to,
                            travelMode: google.maps.DirectionsTravelMode.DRIVING,
                            unitSystem: google.maps.UnitSystem.METRIC
                        };
                        directionsService.route(
                            directionsRequest,

                            function (response, status) {
                            
                                if (status == google.maps.DirectionsStatus.OK) {
                                    
                                    var leg = response.routes[0].legs[0];

                                    var km = response.routes[0].legs[0].distance.text;
                                    var km2 = km.slice(0, -4);
                                    kmplant = parseFloat(km2).toFixed(2);

                                    // value['km'] = km;
                                    // locations.push([value.plant_name, value.latitude, value.longtitude,index,value.plant_location]);
                                    // locations.push(value , kmplant);
                                    value['km'] = kmplant;
                            
                                    if (kmplant <= 15) {

                                        new google.maps.DirectionsRenderer({
                                            map: map,
                                            directions: response,
                                            suppressMarkers: true
                                        });
                                        
                                        console.log(kmplant);
                                        locations.push([value.plant_name, value.latitude, value.longtitude,index,value.plant_location,value.km]);
                                    }
                                    
                                    // console.log(value.km);

                                    // console.log(value);
                                    // set_datagrid(value);

                                } else {
                                    alert("Unable to retrive route");
                                }

                                if(value.km <= 15){ 
                                    // console.log(value.km);
                                    data_plant.push(value); 
                                }

                                // console.log(value.km);
                                

                                $("#grid_vippartner").data("kendoGrid").dataSource.data([]);
                                var gridvippartner = $("#grid_vippartner").data("kendoGrid");

                                $("#grid_partner").data("kendoGrid").dataSource.data([]);
                                var gridpartner = $("#grid_partner").data("kendoGrid");
                                //console.log(data_plant);
                                $.each(data_plant, function( index, value ) {

                                    if (value.preferred_partner == 1) {
                                        gridvippartner.dataSource.add({
                                            plant_name: value.plant_name,
                                            km: value.km,
                                            mat_type: value.mat_type,
                                            strength: value.strength,
                                            c_cost: value.c_cost,
                                            dlv_c: value.dlv_c,
                                            pricelist_no: value.pricelist_no,
                                            truck_size: value.truck_size,
                                            lp: value.lp,
                                            dlv_p_vat: value.dlv_p_vat,
                                            c_price_vat: value.c_price_vat,
                                            vendor_product_code: value.vendor_product_code,
                                            remark: value.remark,
                                            plantid: value.plantid,
                                            dlv_c_vat: value.dlv_c_vat,
                                            min: value.min,
                                            plant_id: value.plant_id,
                                            productname: value.productname,
                                            product_id: value.product_id,
                                            zone: value.zone,
											profit: value.profit,
                                            c_price_vat: value.c_price_vat,
                                            productcode: value.productcode,
                                            lp_disc: value.lp_disc,
                                            c_cost_vat: value.c_cost_vat,
                                            vendor_name: value.vendor_name,
                                            vendor_bank: value.vendor_bank,
                                            vendor_bank_account: value.vendor_bank_account,
                                            vendor_register_address: value.vendor_register_address,

                                        });
                                    } else {
                                        gridpartner.dataSource.add({
                                            plant_name: value.plant_name,
                                            km: value.km,
                                            mat_type: value.mat_type,
                                            strength: value.strength,
                                            c_cost: value.c_cost,
                                            dlv_c: value.dlv_c,
                                            pricelist_no: value.pricelist_no,
                                            truck_size: value.truck_size,
                                            lp: value.lp,
                                            dlv_p_vat: value.dlv_p_vat,
                                            c_price_vat: value.c_price_vat,
                                            vendor_product_code: value.vendor_product_code,
                                            remark: value.remark,
                                            plantid: value.plantid,
                                            dlv_c_vat: value.dlv_c_vat,
                                            min: value.min,
                                            plant_id: value.plant_id,
                                            productname: value.productname,
                                            product_id: value.product_id,
                                            zone: value.zone,
											profit: value.profit,
                                            c_price_vat: value.c_price_vat,
                                            lp_disc: value.lp_disc,
                                            c_cost_vat: value.c_cost_vat,
                                            productcode: value.productcode,
                                            vendor_name: value.vendor_name,
                                            vendor_bank: value.vendor_bank,
                                            vendor_bank_account: value.vendor_bank_account,
                                            vendor_register_address: value.vendor_register_address,
                                        });
                                    }

                                });

                                var infowindow = new google.maps.InfoWindow();

                                var marker, i;

                                for (i = 0; i < locations.length; i++) { 
                                    // console.log(locations[i]);
                                    marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                        map: map,
                                        icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
                                        // icon: icon
                                    });

                                    google.maps.event.addListener(
                                        marker,
                                        "click",
                                        (function (marker, i) {
                                            return function () {
                                                var name = locations[i][0];
                                                var address = locations[i][4];
                                                var latitude = locations[i][1];
                                                var longtitude = locations[i][2];
                                                var km = locations[i][5];
                                    // infowindow.setContent(locations[i][0]);
                                    infowindow.setContent(
                                        "<div style='text-align: center;'><strong>" +
                                        name +
                                        "</strong><br>" +
                                        address +
                                        "<br>" +
                                        latitude + "," + longtitude +
                                        "<br>" +
                                        "ระยะทาง " + km + " กม." +
                                        "</div>"
                                        );
                                        infowindow.open(map, marker);
                                    };
                                    })(marker, i)
                                );
                            }
                            var bounds = new google.maps.LatLngBounds();
                            map.fitBounds(bounds);
                        });
                        // console.log(kmplant);
                        // map.fitBounds(bounds);

                        

                        // var distance = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(lat, long), new google.maps.LatLng(value.latitude, value.longtitude));
                        // var distance = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(value.latitude, value.longtitude), new google.maps.LatLng(lat, long));
                        //     var km = (distance/ 1000).toFixed(1);
                        //     console.log(km);
                        //     if(km <= 15){
                        //         //result.data[index]['km'] = km;
                        //         value['km'] = km;
                                 //data_plant.push(value);
                        //         //locations.push(new google.maps.LatLng(value.latitude, value.longtitude));
                        //         //locations.push({value.plant_name,value.latitude,value.longtitude,index});
                        //         locations.push([value.plant_name, value.latitude, value.longtitude,index,value.plant_location,value.km]);

                        //         // console.log(locations);
                        //     }

                            // } else {
                            //      //result.data[index]['km'] = km;
                            //     value['km'] = km;
                            //     data_plant.push(value);
                            //     //locations.push(new google.maps.LatLng(value.latitude, value.longtitude));
                            //     //locations.push({value.plant_name,value.latitude,value.longtitude,index});
                            //     var latitudepoly = $('#latitudeplant').val();
                            //     var longitudeplant = $('#longitudeplant').val();

                            //     locations.push([value.plant_name, value.latitude, value.longtitude,index,value.plant_location,value.km]);
                            //     // locations.splice(0, 1, ["plant_name",latitudepoly,longitudeplant,index,"plant_location","km"]);
                            //     // locations.push()

                            //     // console.log(locations);
                            // }
                            //console.log(km);
                            // result.data[index] = km;
                            //console.log((distance/ 1000).toFixed(1));
                            //console.log(result);
                        
                        //console.log(locations);
                        //console.log(map);
                    });
                    // console.log(userCoorPath);
                    // console.log(locations);
                    // console.log(locations);

                    // for (i = 0; i < locations.length; i++) {
                    //     // console.log(locations[i]);
                    //     data_plant.push(locations);
                    // }

                    // $.each(locations, function( index, value ) {
                    //     console.log(value);
                    //     data_plant.push(value);
                    // });
                    // set_datagrid(data_plant);
                    //console.log(locations);
                    //locations.sort(compare);
                    //locations.sort();

                    //console.log(locations);
                    // var latitudeplant = $('#latitudeplant').val();
                    // var longitudeplant = $('#longitudeplant').val();
                    // var marker, i;

                    // var infowindow = new google.maps.InfoWindow();
                    //console.log(locations);
                   /* for (i = 0; i < locations.length; i++) {
                        // console.log(locations[i]);
                        // var coords = new google.maps.LatLng(locations[i][1], locations[i][2]);
                        // poly.push(coords);
                        var kmplant = '';
                        var mylatitude = $('#latitudeplant').val();
                        var mylongitude = $('#longitudeplant').val();
                        
                        // var lattitude_value = $('#latitudeplant').val();
                        // var longitude_value = $('#longitudeplant').val();

                        var to = new google.maps.LatLng(mylatitude, mylongitude);
                        // var to = new google.maps.LatLng(lattitude_value, longitude_value);
                        var from = new google.maps.LatLng(locations[i][1], locations[i][2]);
                        //console.log(locations[i]);
                        var directionsService = new google.maps.DirectionsService();
                        var directionsRequest = {
                            origin: from,
                            destination: to,
                            travelMode: google.maps.DirectionsTravelMode.DRIVING,
                            unitSystem: google.maps.UnitSystem.METRIC
                        };

                        directionsService.route(
                            directionsRequest,

                            function (response, status) {
                            
                                if (status == google.maps.DirectionsStatus.OK) {
                                    new google.maps.DirectionsRenderer({
                                        map: map,
                                        directions: response,
                                        suppressMarkers: true
                                    });
                                    var leg = response.routes[0].legs[0];
                                    // makeMarker(leg.start_location, icons.start, "title", map);
                                    // makeMarker(leg.end_location, icons.end, 'title', map);
                                    // var test = distanceInput.value = response.routes[0].legs[0].distance.value / 1000;
                                    // console.log(test);
                                    // console.log(response.routes[0].legs[0].distance.text);

                                    var km = response.routes[0].legs[0].distance.text;
                                    var km2 = km.slice(0, -4);
                                    var kmplant = parseFloat(km2).toFixed(2);
                                    
                                    
                                    //mapkm.push(kmplant);
                                    // value['kmplant'] = kmplant;

                                    location.push([value.plant_name, value.latitude, value.longtitude,index,value.plant_location,kmplant]);

                                    console.log(location);

                                } else {
                                    alert("Unable to retrive route");
                                }
                           
                        });
                        
                        
                    }//for*/
                   
                        // marker = new google.maps.Marker({
                        //     position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                        //     map: map,
                        //     icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
                        //     // icon: icon
                        // });

                        // google.maps.event.addListener(
                        //     marker,
                        //     "click",
                        //     (function (marker, i) {
                        //         return function () {
                        //             var name = locations[i][0];
                        //             var address = locations[i][4];
                        //             var latitude = locations[i][1];
                        //             var longtitude = locations[i][2];
                        //             var km = locations[i][5];
                        //             // infowindow.setContent(locations[i][0]);
                        //             infowindow.setContent(
                        //                 "<div style='text-align: center;'><strong>" +
                        //                 name +
                        //                 "</strong><br>" +
                        //                 address +
                        //                 "<br>" +
                        //                 latitude + "," + longtitude +
                        //                 "<br>" +
                        //                 "ระยะทาง " + km + " กม." +
                        //                 "</div>"
                        //             );
                        //             infowindow.open(map, marker);
                        //         };
                        //     })(marker, i)
                        // );


                        // map.fitBounds(bounds);

                    // console.log(poly);

                    // var bounds = new google.maps.LatLngBounds();
                    // for (c = 0; c < userCoorPath.length; c++) {
                    //     var coords = new google.maps.LatLng(userCoorPath[c][1], userCoorPath[c][2])
                    //     poly.push(coords);
                    //     bounds.extend(coords);
                    // }

                    // var Itinerary = new google.maps.Polyline({
                    //     path: poly,
                    //     geodesic: true,
                    //     strokeColor: "#018FFB",
                    //     strokeOpacity: 0.5,
                    //     strokeWeight: 5
                    // });

                    // Itinerary.setMap(map);
                    // map.fitBounds(bounds);

                    // console.log(data_plant);

                } else {
                    console.log("e");
                }
            },
            error: function (data) {
                console.log("f");
            },
        });

    }

    // function set_datagrid(value){
    //     console.log(value);
    //     $("#grid_vippartner").data("kendoGrid").dataSource.data([]);
    //     var gridvippartner = $("#grid_vippartner").data("kendoGrid");
    //     $("#grid_partner").data("kendoGrid").dataSource.data([]);
    //     var gridpartner = $("#grid_partner").data("kendoGrid");
    //     $.each(data_plant, function( index, value ) {
    //         // console.log(value.preferred_partner);
    //         // console.log(value.brand);
    //         if (value.preferred_partner == 1) {
    //             // console.log(value);
    //             gridvippartner.dataSource.add({
    //                 plant_name: value.plant_name,
    //                 km: value.km,
    //                 mat_type: value.mat_type,
    //                 strength: value.strength,
    //                 c_cost: value.c_cost,
    //                 dlv_c: value.dlv_c,
    //                 pricelist_no: value.pricelist_no,
    //                 truck_size: value.truck_size,
    //                 lp: value.lp,
    //                 dlv_p_vat: value.dlv_p_vat,
    //                 c_price_vat: value.c_price_vat,
    //                 vendor_product_code: value.vendor_product_code,
    //                 remark: value.remark,
    //                 plantid: value.plantid,
    //                 dlv_c_vat: value.dlv_c_vat,
    //                 min: value.min,
    //                 plant_id: value.plant_id,
    //                 productname: value.productname,
    //                 product_id: value.product_id,
    //                 zone: value.zone,
    //             });

    //             $("#grid_vippartner").kendoTooltip({
    //                 filter: ".k-grid-into",
    //                 position: "left",
    //                 // showOn: "click",
    //                 content: function (e) {
    //                     var dataItem = $("#grid_vippartner").data("kendoGrid").dataItem(e.target.closest("tr"));
    //                     return ( '<div><span style="color: #2B2B2B; margin-top: 10px; font-family: PromptMedium; font-weight: 700; font-size: 12px;"> ' +
    //                                 dataItem.plant_name + ' </span><hr><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">ติดต่อ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">เซลล์:</span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">บริษัท: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">จังหวัด: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">ไชลินเดอร์: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">ค่ายุบตัว: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">โซน: ' +
    //                                 dataItem.zone +
    //                                 ' </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">วันที่ทำการ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">เวลาทำการ: </span></div>'
    //                             );
    //                 },
    //             }).data("kendoTooltip");

    //             $("#grid_vippartner").kendoTooltip({
    //                 filter: "td:nth-child(2)", //this filter selects the second column's cells
    //                 position: "right",
    //                 callout: false,
    //                 content: function (e) {
    //                     var dataItem = $("#grid_vippartner").data("kendoGrid").dataItem(e.target.closest("tr"));
    //                     return '<div><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 600; font-size: 11px;">วันที่ทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;">จันทร์ - ศุกร์</span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 600; font-size: 11px;">เวลาทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-weight: 500; font-size: 11px;"> 09.00 - 18.00</span></div>';
    //                 },
    //             }).data("kendoTooltip");

    //         } else {
    //             gridpartner.dataSource.add({
    //                 plant_name: value.plant_name,
    //                 km: value.km,
    //                 mat_type: value.mat_type,
    //                 strength: value.strength,
    //                 c_cost: value.c_cost,
    //                 dlv_c: value.dlv_c,
    //                 pricelist_no: value.pricelist_no,
    //                 truck_size: value.truck_size,
    //                 lp: value.lp,
    //                 dlv_p_vat: value.dlv_p_vat,
    //                 c_price_vat: value.c_price_vat,
    //                 vendor_product_code: value.vendor_product_code,
    //                 remark: value.remark,
    //                 plantid: value.plantid,
    //                 dlv_c_vat: value.dlv_c_vat,
    //                 min: value.min,
    //                 plant_id: value.plant_id,
    //                 productname: value.productname,
    //                 product_id: value.product_id,
    //                 zone: value.zone,
    //             });
    //             // $("#grid_partner").data("kendoGrid").dataSource.data([]);
    //             //     var gridpartner = $("#grid_partner").data("kendoGrid");
    //             //     $.each(data_plant, function (key, value) {
                        
    //             //     });

    //             $("#grid_partner").kendoTooltip({
    //                 filter: "td:nth-child(2)", //this filter selects the second column's cells
    //                 position: "right",
    //                 callout: false,
    //                 content: function (e) {
    //                     var dataItem = $("#grid_partner").data("kendoGrid").dataItem(e.target.closest("tr"));
    //                     return '<div><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 600;">วันที่ทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;"> จันทร์ - ศุกร์</span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 600;">เวลาทำการ: </span><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">09:00 - 18:00</span></div>';
    //                         },
    //             }).data("kendoTooltip");

    //             $("#grid_partner").kendoTooltip({
    //                 filter: ".k-grid-into",
    //                 position: "left",
    //                 content: function (e) {
    //                     var dataItem = $("#grid_partner").data("kendoGrid").dataItem(e.target.closest("tr"));
    //                     return ( '<div><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 12px; font-weight: 700;">' + dataItem.plant_name + '</span><hr><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">ติดต่อ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">เซลล์: </span><br><span style="color: #2B2B2B; font-family:PromptMedium; font-size: 11px; font-weight: 500;">บริษัท: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">จังหวัด: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">ไชลินเดอร์: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">ค่ายุบตัว: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">โซน: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">วันที่ทำการ: </span><br><span style="color: #2B2B2B; font-family: PromptMedium; font-size: 11px; font-weight: 500;">เวลาทำการ: </span></div>' );
    //                 },
    //             }).data("kendoTooltip");
    //         }
    //     });

    // }

    function getplantnotlocation() {

        var lat = $("#latitudeplant").val();
        // console.log(lat);
        var long = $("#longitudeplant").val();
        // console.log("getplantnotlocation");
        var trucksizeplantvalue = document.getElementById("trucksize");
        var trucksizeplant = trucksizeplantvalue.value;
        // console.log(trucksizeplant);
        var mattypevalue = document.getElementById("mattype");
        var mattypeplant = mattypevalue.value;
        // console.log(mattypeplant);
        var strengthplant = $('#strength').val();
        // console.log(strengthplant);
        var cityplant = $("#cityplant").val();

        if(cityplant == ''){
            return false;
        }
        var data = {
            latitudeplant: lat,
            longitudeplant: long,
            trucksizeplant: trucksizeplant,
            mattypeplant: mattypeplant,
            strengthplant: strengthplant,
            cityplant: cityplant
        };

        var data_plant = [];
        var locations = [];
        var mapkm = [];
        var poly = [];
        var locationmapkm = [];
        $("#grid_partner").data("kendoGrid").dataSource.data([]);
        $("#grid_vippartner").data("kendoGrid").dataSource.data([]);

        var urlgetplant = "<?php echo site_url('home/plan_get'); ?>";

        $.ajax(urlgetplant, {
            type: "POST",
            data: data,
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log(result.data);
                
                
                if (result["Type"] == "S") {
                    
                    $.each(result.data, function( index, value ) {

                        var kmplant = '';
                        var mylatitude = $('#latitudeplant').val();
                        var mylongitude = $('#longitudeplant').val();
                        
                        
                        var to = new google.maps.LatLng(mylatitude, mylongitude);
                        var from = new google.maps.LatLng(value.latitude, value.longtitude);
                        var directionsService = new google.maps.DirectionsService();
                        var directionsRequest = {
                            origin: from,
                            destination: to,
                            travelMode: google.maps.DirectionsTravelMode.DRIVING,
                            unitSystem: google.maps.UnitSystem.METRIC
                        };
                        directionsService.route(
                            directionsRequest,

                            function (response, status) {
                            
                                if (status == google.maps.DirectionsStatus.OK) {
                                    
                                    var leg = response.routes[0].legs[0];

                                    var km = response.routes[0].legs[0].distance.text;
                                    var km2 = km.slice(0, -4);
                                    kmplant = parseFloat(km2).toFixed(2);

                                    
                                    value['km'] = kmplant;
                            
                                    if (kmplant <= 15) {

                                        new google.maps.DirectionsRenderer({
                                            map: map,
                                            directions: response,
                                            suppressMarkers: true
                                        });
                                        
                                        console.log(kmplant);
                                        locations.push([value.plant_name, value.latitude, value.longtitude,index,value.plant_location,value.km]);
                                    }
                                    
                                
                                } else {
                                    alert("Unable to retrive route");
                                }

                                if(value.km <= 15){ 
                                    // console.log(value.km);
                                    data_plant.push(value); 
                                }

                                // console.log(value.km);
                                

                                $("#grid_vippartner").data("kendoGrid").dataSource.data([]);
                                var gridvippartner = $("#grid_vippartner").data("kendoGrid");

                                $("#grid_partner").data("kendoGrid").dataSource.data([]);
                                var gridpartner = $("#grid_partner").data("kendoGrid");
                                
                                $.each(data_plant, function( index, value ) {

                                    if (value.preferred_partner == 1) {
                                        gridvippartner.dataSource.add({
                                            plant_name: value.plant_name,
                                            km: value.km,
                                            mat_type: value.mat_type,
                                            strength: value.strength,
                                            c_cost: value.c_cost,
                                            dlv_c: value.dlv_c,
                                            pricelist_no: value.pricelist_no,
                                            truck_size: value.truck_size,
                                            lp: value.lp,
                                            dlv_p_vat: value.dlv_p_vat,
                                            c_price_vat: value.c_price_vat,
                                            vendor_product_code: value.vendor_product_code,
                                            remark: value.remark,
                                            plantid: value.plantid,
                                            dlv_c_vat: value.dlv_c_vat,
                                            min: value.min,
                                            plant_id: value.plant_id,
                                            productname: value.productname,
                                            product_id: value.product_id,
                                            zone: value.zone,
                                            profit: value.profit,
                                            c_price_vat: value.c_price_vat,
                                            productcode: value.productcode,
                                            lp_disc: value.lp_disc,
                                            c_cost_vat: value.c_cost_vat,
                                            vendor_name: value.vendor_name,
                                            vendor_bank: value.vendor_bank,
                                            vendor_bank_account: value.vendor_bank_account,
                                            vendor_register_address: value.vendor_register_address,

                                        });
                                    } else {
                                        gridpartner.dataSource.add({
                                            plant_name: value.plant_name,
                                            km: value.km,
                                            mat_type: value.mat_type,
                                            strength: value.strength,
                                            c_cost: value.c_cost,
                                            dlv_c: value.dlv_c,
                                            pricelist_no: value.pricelist_no,
                                            truck_size: value.truck_size,
                                            lp: value.lp,
                                            dlv_p_vat: value.dlv_p_vat,
                                            c_price_vat: value.c_price_vat,
                                            vendor_product_code: value.vendor_product_code,
                                            remark: value.remark,
                                            plantid: value.plantid,
                                            dlv_c_vat: value.dlv_c_vat,
                                            min: value.min,
                                            plant_id: value.plant_id,
                                            productname: value.productname,
                                            product_id: value.product_id,
                                            zone: value.zone,
                                            profit: value.profit,
                                            c_price_vat: value.c_price_vat,
                                            lp_disc: value.lp_disc,
                                            c_cost_vat: value.c_cost_vat,
                                            productcode: value.productcode,
                                            vendor_name: value.vendor_name,
                                            vendor_bank: value.vendor_bank,
                                            vendor_bank_account: value.vendor_bank_account,
                                            vendor_register_address: value.vendor_register_address,
                                        });
                                    }

                                });

                                var infowindow = new google.maps.InfoWindow();

                                var marker, i;

                                for (i = 0; i < locations.length; i++) { 
                                    // console.log(locations[i]);
                                    marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                        map: map,
                                        icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
                                        // icon: icon
                                    });

                                    google.maps.event.addListener(
                                        marker,
                                        "click",
                                        (function (marker, i) {
                                            return function () {
                                                var name = locations[i][0];
                                                var address = locations[i][4];
                                                var latitude = locations[i][1];
                                                var longtitude = locations[i][2];
                                                var km = locations[i][5];
                                    // infowindow.setContent(locations[i][0]);
                                    infowindow.setContent(
                                        "<div style='text-align: center;'><strong>" +
                                        name +
                                        "</strong><br>" +
                                        address +
                                        "<br>" +
                                        latitude + "," + longtitude +
                                        "<br>" +
                                        "ระยะทาง " + km + " กม." +
                                        "</div>"
                                        );
                                        infowindow.open(map, marker);
                                    };
                                    })(marker, i)
                                );
                            }
                            var bounds = new google.maps.LatLngBounds();
                            map.fitBounds(bounds);
                        });
                       
                    });
                
                } else {
                    $("#grid_partner").data("kendoGrid").dataSource.data([]);
                    $("#grid_vippartner").data("kendoGrid").dataSource.data([]);
                }

            },
            error: function (data) {
                console.log("f");
            },
        });

    }

    // function initAutocomplete() {
    //   var map = new google.maps.Map(document.getElementById("googleMap"), {
    //     center: { lat: -33.8688, lng: 151.2195 },
    //     zoom: 13,
    //     mapTypeId: "roadmap",
    //   });
    //   // Create the search box and link it to the UI element.
    //   var input = document.getElementById("location_searching");
    //   var searchBox = new google.maps.places.SearchBox(input);
    //   // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    //   // Bias the SearchBox results towards current map's viewport.
    //   map.addListener("bounds_changed", () => {
    //     searchBox.setBounds(map.getBounds());
    //   });
    //   let markers = [];
    //   // Listen for the event fired when the user selects a prediction and retrieve
    //   // more details for that place.
    //   searchBox.addListener("places_changed", () => {
    //     var places = searchBox.getPlaces();

    //     if (places.length == 0) {
    //       return;
    //     }
    //     // Clear out the old markers.
    //     markers.forEach((marker) => {
    //       marker.setMap(null);
    //     });
    //     markers = [];
    //     // For each place, get the icon, name and location.
    //     var bounds = new google.maps.LatLngBounds();
    //     places.forEach((place) => {
    //       if (!place.geometry) {
    //         console.log("Returned place contains no geometry");
    //         return;
    //       }
    //       var icon = {
    //         url: place.icon,
    //         size: new google.maps.Size(71, 71),
    //         origin: new google.maps.Point(0, 0),
    //         anchor: new google.maps.Point(17, 34),
    //         scaledSize: new google.maps.Size(25, 25),
    //       };
    //       // Create a marker for each place.
    //       markers.push(
    //         new google.maps.Marker({
    //           map,
    //           icon,
    //           title: place.name,
    //           position: place.geometry.location,
    //         })
    //       );

    //       if (place.geometry.viewport) {
    //         // Only geocodes have viewport.
    //         bounds.union(place.geometry.viewport);
    //       } else {
    //         bounds.extend(place.geometry.location);
    //       }
    //     });
    //     map.fitBounds(bounds);
    //   });
    // }

    // This example creates a 2-pixel-wide red polyline showing the path of
    // the first trans-Pacific flight between Oakland, CA, and Brisbane,
    // Australia which was made by Charles Kingsford Smith.
    // function myMap() {
    //   var map = new google.maps.Map(document.getElementById("googleMap"), {
    //     zoom: 3,
    //     center: { lat: 0, lng: -180 },
    //     mapTypeId: "terrain",
    // });
    //   var flightPlanCoordinates = [
    //   { lat: 37.772, lng: -122.214 },
    //   { lat: 21.291, lng: -157.821 },
    //   { lat: -18.142, lng: 178.431 },
    //   { lat: -27.467, lng: 153.027 },
    //   ];
    //   var flightPath = new google.maps.Polyline({
    //     path: flightPlanCoordinates,
    //     geodesic: true,
    //     strokeColor: "#FF0000",
    //     strokeOpacity: 1.0,
    //     strokeWeight: 2,
    // });
    //   flightPath.setMap(map);
    // }

    function descrtioncase() {
        // console.log('t');
        // console.log($("#task_name").val());
        // validobj.form();
        var taskname = $("#task_name").val();
        console.log(taskname);

        $('#descrip_case').val(taskname);
    }

    // Date Picker
    jQuery(".mydatepicker, #datepicker").datepicker();
    jQuery("#date-range").datepicker({
        toggleActive: true,
        format: 'dd/mm/yyyy',
    });

    jQuery(".datepicker-autoclose").datepicker({
        autoclose: true,
        todayHighlight: true
    });
    // jQuery('#datepicker-autoclose').datepicker({
    //     autoclose: true,
    //     todayHighlight: true
    // });

    // $('#datetimepicker1').datetimepicker({
    //   // language: 'pt-BR'
    // });

    // For select 2
    $(".select2").select2();
    //$('.selectpicker').selectpicker();

    // $('.date-format').bootstrapMaterialDatePicker({ format: 'DD/MM/YYYY HH:mm' });

    // var casedate = document.getElementById('casedate');
    // var casedatesrt = casedate.value.replace("T"," ");
    // console.log(casedatesrt);

    // var case_date = document.getElementById('case_date');
    // case_date.value = casedatesrt;

    // Daterange picker
    $(".input-daterange-datepicker").daterangepicker({
        buttonClasses: ["btn", "btn-sm"],
        applyClass: "btn-danger",
        cancelClass: "btn-inverse",
    });
    $(".input-daterange-timepicker").daterangepicker({
        timePicker: true,
        format: "MM/DD/YYYY h:mm A",
        timePickerIncrement: 30,
        timePicker12Hour: true,
        timePickerSeconds: false,
        buttonClasses: ["btn", "btn-sm"],
        applyClass: "btn-danger",
        cancelClass: "btn-inverse",
    });
    $(".input-limit-datepicker").daterangepicker({
        format: "MM/DD/YYYY",
        minDate: "06/01/2015",
        maxDate: "06/30/2015",
        buttonClasses: ["btn", "btn-sm"],
        applyClass: "btn-danger",
        cancelClass: "btn-inverse",
        dateLimit: {
            days: 6,
        },
    });

    var actualBtn = document.getElementById("file_upload");

    var fileChosen = document.getElementById("file-chosen");

    actualBtn.addEventListener("change", function () {
        fileChosen.value = this.files[0].name;
    });

    var emailfileBtn = document.getElementById("emailfile-btn");
    var fileEmailChosen = document.getElementById("fileemail-chosen");

    emailfileBtn.addEventListener("change", function () {
        fileEmailChosen.textContent = this.files[0].name;
    });
</script>

<!-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3v5RF7dmuksJLV2soXcaKRreDe7kckGY&callback=myMap&libraries=places&v=weekly"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3v5RF7dmuksJLV2soXcaKRreDe7kckGY&callback=myMap"></script> -->

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB3v5RF7dmuksJLV2soXcaKRreDe7kckGY&callback=initAutocomplete&libraries=&v=weekly" defer></script> -->

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;language=th&key=AIzaSyBWaeWMJY8V-oq8udvy6JXnuPdEcjOj2sE&callback=myMap&libraries=places&v=weekly" defer></script>

<script>
    $(function () {

        $(".panel-clrom").click(function () {
            $(this).toggleClass("on");
        });

        $(".panel-collapseom").on("show.bs.collapse", function () {
            $(this).siblings(".panel-headingom").addClass("active");
        });

        $(".panel-collapseom").on("hide.bs.collapse", function () {
            $(this).siblings(".panel-headingom").removeClass("active");
        });

        // $(".panel-clrom").click(function () {
        //     $(this).toggleClass("on");
        // });

        // $(".panel-collapse").on("show.bs.collapse", function () {
        //     $(this).siblings(".panel-heading").addClass("active");
        // });

        // $(".panel-collapse").on("hide.bs.collapse", function () {
        //     $(this).siblings(".panel-heading").removeClass("active");
        // });

        // $(".panel-collapseitem").on("show.bs.collapse", function () {
        //     $(".item").show();
        // });

        // $(".panel-collapseitem").on("hide.bs.collapse", function () {
        //     $(".item").hide();
        // });

    });
</script>
