 <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid" style="margin-top: -10px;">
        <div class="card-group row1">
            <!-- card -->
            <div class="card o-income">
                <div class="card-body">
                    <div class="page-titles d-flex align-items-center no-block">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="" style="margin-top: 3px;">
                                    <span class="hidden-xs-down"><?php echo genLabel('LBL_CONTACT_INFORNMATION'); ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="ml-auto" style="margin-top: 2px;">
                            <ul class="list-inline" style="font-size: 11px; margin-bottom: auto; ">
                                <li class="disabled">
                                    <a href="">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/phoneb.png'); ?>" width="15" height="15"/>
                                        </i> <?php echo genLabel('LBL_CALL_IN') ?>
                                    </a>
                                </li>
                                <li style="background: #018FFB;">
                                    <a href="#" style="color: #ffffff;">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/savew.png'); ?>" width="15" height="15"/>
                                        </i> <?php echo genLabel('LBL_SAVE') ?>
                                    </a>
                                </li>
                                <li class="disabled">
                                    <a href="">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="15" height="15"/>
                                        </i> <?php echo genLabel('LBL_MORE_INFO') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="$.clearAll();" style="color: #FEB018;">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/clearo.png'); ?>" width="15" height="17"/>
                                        </i> <?php echo genLabel('LBL_CLEAR') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" id="adsearch">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/advancesearchb.png'); ?>" width="20" height="15"/>
                                        </i> <?php echo genLabel('LBL_ADVANCE_SEARCH') ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 align-self-center">
                        <div class="tab-content tabcontent-border">
                            <div class="tab-pane active" id="status1" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-12" style="background-color: #fff;">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group m-t-5 row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;"><?php echo genLabel('LBL_CONTACT_CODE') ?></label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="contact_code" id="contact_code">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group m-t-5 row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_STATUS') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="status_contact" id="status_contact">
                                                                <option selected></option>
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_FRIST_NAME') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="title_name" id="title_name">
                                                                        <option selected></option>
                                                                        <option>1</option>
                                                                        <option>2</option>
                                                                        <option>3</option>
                                                                        <option>4</option>
                                                                        <option>5</option>
                                                                    </select>
                                                                </div>
                                                                &nbsp;
                                                                <input type="text" class="form-control" aria-label="Text input with dropdown button" name="fristname_contact" id="fristname_contact">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_LAST_NAME') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="lastname_contact" id="lastname_contact">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_PHONE') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="phone_contact" id="phone_contact">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_EMAIL') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="email_contact" id="email_contact">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_LINE_ID') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="lineid_contact" id="lineid_contact">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_FACEBOOK') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="facebook_contact" id="facebook_contact">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_REMARK') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <textarea class="form-control" rows="3" name="remark_contact" id="remark_contact"></textarea>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_EMOTION') ?>
                                                        </label>
                                                        <div class="row" style="margin-left: 1px;">
                                                            <div class="col-sm-4">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" name="natured" id="natured">
                                                                    <span class="custom-control-label"><i><img src="<?php echo site_assets_url('images/icons/emo01.png'); ?>" width="25" height="25"/></i></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" name="normal" id="normal">
                                                                    <span class="custom-control-label">
                                                                        <i><img src="<?php echo site_assets_url('images/icons/emo02.png'); ?>" width="25" height="25"/></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" name="morose" id="morose">
                                                                    <span class="custom-control-label">
                                                                        <i><img src="<?php echo site_assets_url('images/icons/emo03.png'); ?>" width="25" height="25"/></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_SITE_CODE') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="sitecode_contact" id="sitecode_contact">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6">
                                                <form class="form">
                                                    <div class="form-group row">
                                                        <label for="example-text-input" class="col-sm-3 control-label col-form-label" style="font-size: 11px; color: #2B2B2B; text-align: right;">
                                                            <?php echo genLabel('LBL_DATE_UPDATE') ?>
                                                        </label>
                                                        <div class="col-9">
                                                            <input class="form-control" type="text" name="dateupdate_contact" id="dateupdate_contact">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: -15px;">
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-success d-none d-lg-block m-l-15 btncallin" id="searching">
                                                    <i>
                                                        <img src="<?php echo site_assets_url('images/icons/searching.png'); ?>" width="18" height="15"/>
                                                    </i> <?php echo genLabel('LBL_SEARCHING') ?> 
                                                </button>
                                                <!-- The Modal -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
            <!-- card -->
            &nbsp;
            <div class="card o-income">
                <div class="card-body">
                    <div class="page-titles d-flex align-items-center no-block">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#status1" style="margin-top: 3px;">
                                    <span class="hidden-xs-down"><?php echo genLabel('LBL_BILL_TO_INFORMATION') ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="ml-auto" style="margin-top: 2px;">
                            <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                                <li class="disabled">
                                    <a href="">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/statusb.png'); ?>" width="15" height="15"/>
                                        </i> <?php echo genLabel('LBL_STATUS') ?>
                                    </a>
                                </li>
                                <li style="background: #785DD0;">
                                    <a href="#" style="color: #ffffff;">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/copyw.png'); ?>" width="15" height="15"/>
                                        </i> <?php echo genLabel('LBL_COPY') ?>
                                    </a>
                                </li>
                                <li class="disabled">
                                    <a href="">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/advancesearchb.png'); ?>" width="20" height="15"/>
                                        </i> <?php echo genLabel('LBL_ADVANCE_SEARCH') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="$.clearAllBill();" style="color: #FEB018;">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/clearo.png'); ?>" width="15" height="17"/>
                                        </i> <?php echo genLabel('LBL_CLEAR') ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-12 align-self-center">
                        <div class="tab-content tabcontent-border">
                            <div class="tab-pane active" id="status1" role="tabpanel">
                                <form>
                                    <div class="form-group m-t-5 row">
                                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                            <?php echo genLabel('LBL_COMPANY_NAME') ?>
                                        </label>
                                        <div class="col-sm-10">
                                          <input class="form-control formbill" type="text" name="companyname" id="companyname" readonly>
                                      </div>
                                  </div>  
                              </form>
                              <form style="margin-top: -15px;">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_BRACH') ?>
                                    </label>
                                    <div class="col-sm-10">
                                      <input class="form-control formbill" type="text" name="brach" id="brach" readonly>
                                  </div>
                              </div>  
                          </form>
                          <form style="margin-top: -15px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                            <?php echo genLabel('LBL_TAX_NO') ?>
                                        </label>
                                        <div class="col-sm-8">
                                            <input class="form-control formbill" type="text" name="taxno" id="taxno" readonly>
                                        </div>
                                    </div>  
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                            <?php echo genLabel('LBL_TELEPHONE') ?>
                                        </label>
                                        <div class="col-sm-8">
                                            <input class="form-control formbill" type="text" name="telephone" id="telephone" readonly>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </form>
                        <form style="margin-top: -15px;">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                    <?php echo genLabel('LBL_ADDRESS') ?>
                                </label>
                                <div class="col-sm-10">
                                  <textarea class="form-control formbill" rows="3" name="address" id="address" readonly></textarea>
                              </div>
                          </div>  
                      </form>
                      <form style="margin-top: -15px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_CONTACT_PERSON') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control formbill" type="text" name="contact_person" id="contact_person" readonly>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_CONTACT_TEL') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control formbill" type="text" name="contact_tel" id="contact_tel" readonly>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </form>
                    <form style="margin-top: -15px;">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                <?php echo genLabel('LBL_ADDRESS_BILL_TO') ?>
                            </label>
                            <div class="col-sm-10">
                              <textarea class="form-control formbill" rows="4" name="address_bill_to" id="address_bill_to" readonly></textarea>
                          </div>
                      </div>  
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
</div>
<div class="card-group">
    <!-- card -->
    <div class="card o-income">
        <div class="card-body">
            <div class="page-titles d-flex align-items-center no-block">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#status1" style="margin-top: 3px;">
                            <span class="hidden-xs-down"><?php echo genLabel('LBL_CASE_DETAIL') ?></span>
                        </a>
                    </li>
                </ul>
                <div class="ml-auto" style="margin-top: 2px;">
                    <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                        <li style="background: #018FFB;">
                            <a href="#" style="color: #ffffff;">
                                <i>
                                    <img src="<?php echo site_assets_url('images/icons/savew.png'); ?>" width="15" height="15"/>
                                </i> <?php echo genLabel('LBL_SAVE') ?>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="">
                                <i>
                                    <img src="<?php echo site_assets_url('images/icons/smsb.png'); ?>" width="18" height="15"/>
                                </i> <?php echo genLabel('LBL_SMS') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i>
                                    <img src="<?php echo site_assets_url('images/icons/Email.png'); ?>" width="18" height="15"/>
                                </i> <?php echo genLabel('LBL_EMAIL') ?>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="">
                                <i>
                                    <img src="<?php echo site_assets_url('images/icons/callInb.png'); ?>" width="15" height="15"/>
                                </i> <?php echo genLabel('LBL_CALL_IN') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="$.clearAllCase();" style="color: #FEB018;">
                                <i>
                                    <img src="<?php echo site_assets_url('images/icons/clearo.png'); ?>" width="15" height="17"/>
                                </i> <?php echo genLabel('LBL_CLEAR') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 align-self-center">
                <div class="tab-content tabcontent-border">
                    <div class="tab-pane active" id="status1" role="tabpanel">
                        <form>
                            <div class="form-group m-t-5 row">
                                <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                    <?php echo genLabel('LBL_CASE_ID') ?>
                                </label>
                                <div class="col-sm-10">
                                  <input class="form-control" type="text" name="caseid" id="caseid">
                              </div>
                          </div>  
                      </form>
                      <form style="margin-top: -15px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_PROBLEM_NAME') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="problem_name" id="problem_name">
                                            <option selected></option>
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
                            </div>
                        </div>
                    </form>
                    <form style="margin-top: -15px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_STATUS') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="status_case" id="status_case">
                                            <option selected></option>
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
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_CONTACT_CHANNEL') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="contact_channel_case" id="contact_channel_case">
                                            <option selected></option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </form>
                    <form style="margin-top: -15px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_RESPONSE') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="response_case" id="response_case">
                                            <option selected></option>
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
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_ASSIGN_TO') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="assign_to_case" id="assign_to_case">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i>
                                                        <img src="<?php echo site_assets_url('images/icons/assign.png'); ?>" width="18" height="15"/>
                                                    </i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </form>
                    <form style="margin-top: -15px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_DUE_DATE') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control mydatepicker" placeholder="" name="due_date_case" id="due_date_case">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="15" height="15"/></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                        <?php echo genLabel('LBL_CLOSE_DATE') ?>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control mydatepicker" placeholder="" name="close_date_case" id="close_date_case">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="15" height="15"/></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </form>
                    <form style="margin-top: -15px;">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                <?php echo genLabel('LBL_DETAIL') ?>
                            </label>
                            <div class="col-sm-10">
                              <textarea class="form-control" rows="3" name="detail_case" id="detail_case"></textarea>
                          </div>
                      </div>  
                  </form>
                  <form style="margin-top: -15px;">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                            <?php echo genLabel('LBL_NOTE') ?>
                        </label>
                        <div class="col-sm-10">
                          <textarea class="form-control" rows="3" name="note_case" id="note_case"></textarea>
                      </div>
                  </div>  
              </form>
              <form style="margin-top: -15px;">
                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                        <?php echo genLabel('LBL_ATTACH_FILE') ?>
                    </label>
                    <div class="col-sm-10">
                        <div class="input-group mb-3">
                            <button class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04" style="font-size: 11px; color: #2B2B2B; border-radius: 5px;">Upload</button>
                            &nbsp;
                            <input class="form-control" type="text" placeholder="" readonly>
                        </div>
                    </div>
                </div>
            </form>
            <form style="margin-top: -15px;">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                <?php echo genLabel('LBL_CRATOR') ?>
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="cartor_case" id="cartor_case">
                            </div>
                        </div>  
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 control-label col-form-label" style="font-size: 11px; color: #2B2B2B;">
                                <?php echo genLabel('LBL_DATE_CREATE') ?>
                            </label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="date_create" id="date_create">
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
<!-- card -->
&nbsp;
<div class="card o-income">
    <div class="card-body">
        <div class="d-flex page-titles d-flex align-items-center no-block">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#status0" role="tab" aria-selected="true" style="margin-top: 3px;">
                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                        <span class="hidden-xs-down">
                            <?php echo genLabel('LBL_FAQ') ?>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#menu1" style="margin-top: 3px;">
                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                        <span class="hidden-xs-down">
                            <?php echo genLabel('LBL_KM') ?>
                        </span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#menu2" style="margin-top: 3px;">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">
                                <?php echo genLabel('LBL_HISTORY_CASE') ?>
                            </span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu3" style="margin-top: 3px;">
                                <span class="hidden-sm-up"><i class="ti-email"></i></span>
                                <span class="hidden-xs-down">
                                    <?php echo genLabel('LBL_HISTORY_ORDER') ?>
                                </span></a>
                            </li>
                        </ul>
                        <div class="ml-auto" style="margin-top: 2px;">
                            <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                                <li class="disabled">
                                    <a href="#">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/sendnoticeb.png'); ?>" width="15" height="15"/>
                                        </i> <?php echo genLabel('LBL_SEND_NOTICE') ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 align-self-center">
                        <div class="tab-content tabcontent-border">
                            <div class="tab-pane active" id="status0" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-12" style="background-color: #fff;">
                                        <form id="form_faq" action="#" method="POST">
                                            <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="select_faq" id="select_faq">
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
                                                    <input class="form-control" type="text" placeholder="Search Text" style="font-size: 11px; color: #2B2B2B;" name="faqname" id="faqname">
                                                    &nbsp;
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary fillterfaq" type="button" style="font-size: 11px; color: #2B2B2B; border-radius: 10px;">
                                                            <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15"/>
                                                            <?php echo genLabel('LBL_SEARCHING') ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                        <form id="form_km" action="#" method="POST">
                                            <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                <select class="form-control select2" style="font-size: 11px; color: #2B2B2B;" name="select_km" id="select_km">
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
                                                    <input class="form-control" type="text" placeholder="Search Text" style="font-size: 11px; color: #2B2B2B;" name="knowledgebasename" id="knowledgebasename">
                                                    &nbsp;
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary fillterkm" type="button" style="font-size: 11px; color: #2B2B2B; border-radius: 10px;"><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15"/> ค้นหา</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    <div id="grid_km"></div>
                                </div>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <h1>
                                History Case
                            </h1>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <h1>
                                History Order
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="modalAccountsearch" class="modal modal-popup" style="overflow: unset;">
  <div class="modal-content">
    <div class="modal-header">
        <span style="font-size: 16px; font-family: PromptMedium; color: #2B2B2B;">
            <b><?php echo genLabel('LBL_ACCOUNT_SEARCH') ?></b>
        </span>
        <span class="close" id="closeAccountsearch"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span>
    </div>
    <div class="modal-body" style="margin-top: 10px;">
        <div class="col-12">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-1" style="margin: auto;">
                        <p style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
                            <?php echo genLabel('LBL_SEARCH_CONDITIONS') ?>
                        </p>
                    </div>
                    <div class="col-sm" style="border: 1px solid #EDEDED; border-radius: 5px;">
                        <form id="form_account" action="#" method="POST">
                            <div class="row" style="padding-top: 20px; padding-left: 20px;">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2B2B2B;"><?php echo genLabel('LBL_FIRSTNAME_ACCOUNT') ?>:</label>
                                                <div class="col-sm">
                                                    <input class="form-control formaccountsearch" name="accountname" id="accountname" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2B2B2B;"><?php echo genLabel('LBL_CONTACT_NO') ?> :</label>
                                                <div class="col-sm">
                                                    <input class="form-control formaccountsearch" name="mobile" id="mobile" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: -25px;">
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2B2B2B"><?php echo genLabel('LBL_PHONE_ACCOUNT') ?>:</label>
                                                <div class="col-sm">
                                                    <input class="form-control formaccountsearch" name="phone" id="phone" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2B2B2B"><?php echo genLabel('LBL_LINE_ID') ?> :</label>
                                                <div class="col-sm">
                                                    <input class="form-control formaccountsearch" name="lineid" id="lineid" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="control-label col-form-label" style="font-size: 11px; color: #2B2B2B"><?php echo genLabel('LBL_FACEBOOK') ?> :</label>
                                                <div class="col-sm">
                                                    <input class="form-control formaccountsearch" name="facebookname" id="facebookname" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-success d-none d-lg-block btncallin fillter" style="float: none;">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15"/>
                                        </i> <?php echo genLabel('LBL_SEARCHING') ?>
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
</div>

<div id="modalSearching" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <!-- <span class="close text-right">&times;</span> -->
    <!-- <span class="close text-right"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span> -->
    <div class="modal-header">
        <span style="font-size: 16px; font-family: PromptMedium; color: #2B2B2B;">
            <b><?php echo genLabel('LBL_SEARCHING_MODAL') ?></b>
        </span>
        <span class="close" id="closesearching"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span>
        <br>
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
                                            <div id="googleMap" style="width:100%;height:350px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6 colsm6searching">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_CONTACT_CODE') ?>" style="font-size: 11px;" name="contactcode_searching" id="contactcode_searching" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_CONTACT_DATE') ?>" style="font-size: 11px;" name="contactdate_searching" id="contactdate_searching">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-left: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_CONTACT_NAME') ?>" style="font-size: 11px;" name="contactname_searching" id="contactname_searching">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_CONTACT_TEL') ?>" style="font-size: 11px;" name="contacttel_searching" id="contacttel_searching">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-left: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_LINE_ID') ?>" style="font-size: 11px;" name="lineid_searching" id="lineid_searching">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_FACEBOOK') ?>" style="font-size: 11px;" name="facebook_searching" id="facebook_searching" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-left: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_PROJECT_ADDRESS') ?>" style="font-size: 11px;" name="projectaddress_searching" id="projectaddress_searching">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 colsm6searching">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control formsearching" placeholder="<?php echo genLabel('LBL_LOCATION') ?>" style="height: 30px; font-size: 11px;" name="location_searching" id="location_searching">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" style="height: 30px; background-color: #ffffff; border: 0px;">
                                                                    <i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15"/></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-left: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <select class="form-control formsearching" style="height: 30px; font-size: 11px; color: #2B2B2B;  font-family: PromptMedium;" name="trucksize" id="trucksize">
                                                            <option><?php echo genLabel('LBL_TRUCK_SIZE') ?></option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_QUEUE_QTY') ?>" style="font-size: 11px;" name="queueqty" id="queueqty">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-right: 1px solid #EDEDED; border-left: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <select class="form-control formsearching" style="height: 30px; font-size: 11px; color: #2B2B2B;  font-family: PromptMedium;" name="mattype" id="mattype" >
                                                            <option><?php echo genLabel('LBL_MAT_TYPE') ?></option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <select class="form-control formsearching" style="height: 30px; font-size: 11px; color: #2B2B2B;  font-family: PromptMedium;" name="strengrh" id="strengrh">
                                                            <option><?php echo genLabel('LBL_STRENGTH') ?></option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-right: 1px solid #EDEDED; border-left: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <select class="form-control formsearching" style="height: 30px; font-size: 11px; color: #2B2B2B;  font-family: PromptMedium;" name="objective" id="objective">
                                                            <option><?php echo genLabel('LBL_OBJECTIVE') ?></option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 colsm6searching" style="border: 0px; border-top: 1px solid #EDEDED; border-right: 1px solid #EDEDED;">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control formsearching mydatepicker" placeholder="<?php echo genLabel('LBL_USAGE_DATE') ?>" style="height: 30px; font-size: 11px;" name="usage_date" id="usage_date">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" style="height: 30px; background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/Calendar.png'); ?>" width="15" height="15"/></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 colsm6searching">
                                                    <div class="col-sm-12 m-t-5 colsm12searching">
                                                        <input class="form-control formsearching" type="text" placeholder="<?php echo genLabel('LBL_DESCRTION') ?>" style="font-size: 11px;" name="descrtion_searching" id="descrtion_searching">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 m-t-5" style="text-align: right;">   
                                                    <button id="savesearchingbtn" type="button" class="btn btn-success d-none d-lg-block m-l-15 btnsavesearching"><i><img src="<?php echo site_assets_url('images/icons/savebuttono.png'); ?>" width="15" height="15"/></i> <?php echo genLabel('LBL_CREATE_ORDER') ?></button> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h4 style="color: #2B2B2B; font-family: PromptMedium; font-size: 12px;">
                                <?php echo genLabel('LBL_VIP_PARTNER') ?>
                            </h4>
                            <div class="table-responsive text-center">
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
                            </div>
                        </div>
                        <div class="col-12 m-t-10">
                            <h4 style="color: #2B2B2B; font-family: PromptMedium; font-size: 12px;">
                                <?php echo genLabel('LBL_PARTNER') ?>
                            </h4>
                            <div class="table-responsive text-center">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<div id="modalAdsearch" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2B2B2B;"><b><i>Order Inquiry</i></b></span>
            <span class="close2"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span>
        </div>
        <div class="modal-body" style="margin-top: 10px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                          <div class="col-2" style="margin: auto; text-align: center; font-family: PromptMedium; font-size: 11px; color: #2B2B2B;">
                              <b>ค้นหาใบจอง</b>
                          </div> 
                          <div class="col-10" style="border: 1px solid #EDEDED; font-family: PromptMedium; font-size: 11px; color: #2B2B2B;">
                            <div class="row m-t-10" style="padding-left: 15px;">
                                <div class="col-sm">
                                    <form class="form">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label">Contact ID :</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm">
                                    <form class="form">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label">เบอร์โทร :</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm">
                                    <form class="form">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label">ไลน์ :</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row text-right" style="margin-right: -10px;">
                                <div class="col-sm">
                                    <button type="button" class="btn btn-success d-none d-lg-block btncallin" style="margin-top: -35px;">
                                        <i>
                                            <img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="15" height="15"/>
                                        </i> ค้นหา 
                                    </button>
                                </div>
                            </div>
                            <div class="row" style="padding-left: 15px; margin-top: -20px;">
                                <div class="col-sm">
                                    <form class="form">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label">ชื่อ :</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm">
                                    <form class="form">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label">โครงการ :</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm">
                                    <form class="form">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="control-label col-form-label">ไซต์งานลูกค้า :</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row" style="padding-left: 15px; margin-top: -20px;">
                                <div class="col-sm-12" style="text-align: center;">
                                    <div class="input-daterange input-group" id="date-range" style="padding-bottom: 10px; margin-left: 290px;">
                                        <input type="text" class="form-control col-sm-2" name="start" />
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="input-group-append">
                                            <span style="margin: auto; color: #2B2B2B; font-size: 11px;"> - </span>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" class="form-control col-sm-2" name="end" />
                                        &nbsp;&nbsp;&nbsp;
                                        <div class="input-group-append">
                                            <img src="<?php echo site_assets_url('images/icons/Calendar.png'); ?>" width="15" height="15" style="margin: auto;"/>
                                            &nbsp;
                                            <img src="<?php echo site_assets_url('images/icons/slidedownb.png'); ?>" width="7" height="5" style="margin: auto;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-sm-12 text-center" style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
                            ค้นพบออเดอร์ทั้งหมด xxx รายการ
                        </div>
                            <!-- <div class="col-sm-2">
                                <button type="button" class="btn d-none d-lg-block m-l-15 btnsave "><i class="fa fa-user-plus"></i> เพิ่มข้อมูล</button>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="container demo col-sm-12">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading panel-clr" role="tab" id="headingOne">
                                      <div class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                          <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-1">
                                                    #1
                                                </div>
                                                <div class="col-sm-10" style="text-align: center;">
                                                    COO1000 - วันศุกร์ที่ 31/07/2563 เวลา 09.41 น - ใช้วันเสาร์ที่ 01/08/2563 เวลา 09.00 น.
                                                </div>
                                            </div> 
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                              <div class="panel-body">
                                <div class="col-sm-12" style="background-color: #EDEDED; font-size: 11px; color: #2B2B2B;">
                                    <div class="row" style="padding-left: 10px; padding-top: 5px;">
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        ชื่อลูกค้า
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-2">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        เบอร์โทร  
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-2">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        ไลน์  
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-2">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        เฟสบุ๊ค  
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        โครงการ  
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 10px; margin-top: -20px;">
                                        <div class="col-sm-12">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        ที่ตั้ง  
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: -20px; padding-left: 10px;">
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        ประเภทคอนกรีต  
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        กำลังอัด   
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form> 
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        ต้องการใช้   
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form> 
                                        </div>
                                        <div class="col-sm-3">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        ขนาดรถ   
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form> 
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 10px; margin-top: -20px;">
                                        <div class="col-sm-12">
                                            <form class="form">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="control-label col-form-label">
                                                        รายละเอียดเพิ่มเติม   
                                                    </label>
                                                    <div class="col-sm">
                                                        <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                                        <div class="table-responsive m-t-10">
                                            <table class="table table-striped" style="border: 1px solid #EDEDED;">
                                                <thead class="text-center" style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
                                                    <tr>
                                                        <th style="width: 5%;">ได้คิว</th>
                                                        <th style="width: 15%;">แพล้นท์</th>
                                                        <th>ติดต่อ</th>
                                                        <th style="width: 11%;">วันที่ใช้</th>
                                                        <th style="width: 7%;">เวลา</th>
                                                        <th>ST</th>
                                                        <th>ราคา/คิว</th>
                                                        <th>ค่าขนส่ง</th>
                                                        <th style="width: 10%;">ค่าคอนกรีต</th>
                                                        <th>กำไร</th>
                                                        <th>ลูกค้า</th>
                                                        <th style="width: 10%;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center table-bordered" style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
                                                    <tr style="background-color: #ffffff;">
                                                        <td class="centered custom-checkbox">
                                                            <input type="checkbox" style="text-align:center;">
                                                        </td>
                                                        <td>โมอาย คอลเซนเตอร์</td>
                                                        <td>
                                                            <img src="<?php echo site_assets_url('images/icons/phonetable.png'); ?>" width="15" height="15"/>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" placeholder="01/08/2563">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" placeholder="10:35">
                                                        </td>
                                                        <td>210</td>
                                                        <td>
                                                            <input class="form-control" type="text" placeholder="2690.0">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" placeholder="400 / คิว">
                                                        </td>
                                                        <td><b>5380.00</b></td>
                                                        <td>209.60</td>
                                                        <td>
                                                            <input class="form-control" type="text" placeholder="รอแจ้ง">
                                                        </td>
                                                        <td>
                                                            <label>
                                                                <a href="#" style="color: #000;">แก้ไข</a>
                                                            </label>
                                                            l
                                                            <label>
                                                                <a href="#" style="color: #000;">ลบ</a>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading panel-clr" role="tab" id="headingTwo">
                          <div class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-1">
                                        #1
                                    </div>
                                    <div class="col-sm-10" style="text-align: center;">
                                        COO1000 - วันศุกร์ที่ 31/07/2563 เวลา 09.41 น - ใช้วันเสาร์ที่ 01/08/2563 เวลา 09.00 น.
                                    </div>
                                </div> 
                            </div>
                        </a>
                    </div>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <div class="panel-body">
                    <div class="col-sm-12" style="background-color: #EDEDED; font-size: 11px; color: #2B2B2B;">
                        <div class="row" style="padding-left: 10px; padding-top: 5px;">
                            <div class="col-sm-3">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            ชื่อลูกค้า
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-2">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            เบอร์โทร  
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-2">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            ไลน์  
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-2">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            เฟสบุ๊ค  
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            โครงการ  
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row" style="padding-left: 10px; margin-top: -20px;">
                            <div class="col-sm-12">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            ที่ตั้ง  
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row" style="margin-top: -20px; padding-left: 10px;">
                            <div class="col-sm-3">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            ประเภทคอนกรีต  
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            กำลังอัด   
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form> 
                            </div>
                            <div class="col-sm-3">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            ต้องการใช้   
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form> 
                            </div>
                            <div class="col-sm-3">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            ขนาดรถ   
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form> 
                            </div>
                        </div>
                        <div class="row" style="padding-left: 10px; margin-top: -20px;">
                            <div class="col-sm-12">
                                <form class="form">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="control-label col-form-label">
                                            รายละเอียดเพิ่มเติม   
                                        </label>
                                        <div class="col-sm">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row" style="padding-left: 10px; padding-right: 10px;">
                            <div class="table-responsive m-t-10">
                                <table class="table table-striped" style="border: 1px solid #EDEDED;">
                                    <thead class="text-center" style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
                                        <tr>
                                            <th style="width: 5%;">ได้คิว</th>
                                            <th style="width: 15%;">แพล้นท์</th>
                                            <th>ติดต่อ</th>
                                            <th style="width: 11%;">วันที่ใช้</th>
                                            <th style="width: 7%;">เวลา</th>
                                            <th>ST</th>
                                            <th>ราคา/คิว</th>
                                            <th>ค่าขนส่ง</th>
                                            <th style="width: 10%;">ค่าคอนกรีต</th>
                                            <th>กำไร</th>
                                            <th>ลูกค้า</th>
                                            <th style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center table-bordered" style="font-size: 11px; color: #2B2B2B; font-family: PromptMedium;">
                                        <tr style="background-color: #ffffff;">
                                            <td class="centered custom-checkbox">
                                                <input type="checkbox" style="text-align:center;">
                                            </td>
                                            <td>โมอาย คอลเซนเตอร์</td>
                                            <td>
                                                <img src="<?php echo site_assets_url('images/icons/phonetable.png'); ?>" width="15" height="15"/>
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" placeholder="01/08/2563">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" placeholder="10:35">
                                            </td>
                                            <td>210</td>
                                            <td>
                                                <input class="form-control" type="text" placeholder="2690.0">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" placeholder="400 / คิว">
                                            </td>
                                            <td><b>5380.00</b></td>
                                            <td>209.60</td>
                                            <td>
                                                <input class="form-control" type="text" placeholder="รอแจ้ง">
                                            </td>
                                            <td>
                                                <label>
                                                    <a href="#" style="color: #000;">แก้ไข</a>
                                                </label>
                                                l
                                                <label>
                                                    <a href="#" style="color: #000;">ลบ</a>
                                                </label>
                                            </td>
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
</div>
</div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span style="font-size: 16px; font-family: PromptMedium; color: #2B2B2B;">
                <b><?php echo genLabel('LBL_ORDER_MANAGEMENT') ?></b>
            </span>
            <span class="close" id="closeordermanagement"><img src="<?php echo site_assets_url('images/icons/close.png'); ?>" width="15" height="15"/></span>
            <br>
        </div>
        <div class="modal-body" style="margin-top: 10px;">
            <div class="col-12">
                <div class="d-flex align-items-center no-block">
                    <ul class="nav nav-tabs" role="tablist" style="font-family: PromptMedium; border-bottom: 0px; padding-left: 15px;">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#bi" style="font-size: 12px; border-color: #E5E5E5 #E5E5E5 #fff; background-color: #ffffff; color: #2B2B2B;"><b><?php echo genLabel('LBL_BASIC_INFORMATION') ?></b></a>
                        </li>
                    </ul>
                    <div class="ml-auto" style="margin-top: 0px;">
                        <ul class="list-inline" style="font-size: 11px; margin-bottom: auto;">
                            <li>
                                    <!-- <a href="#">
                                       Save
                                   </a> -->
                                   <button type="button" class="btn btn-success d-none d-lg-block save" id="">
                                    <b><?php echo genLabel('LBL_SAVE') ?></b> 
                                </button>
                            </li>
                            <li>
                                    <!-- <a href="#">
                                       Cancel
                                   </a> -->
                                   <button type="button" class="btn d-none d-lg-block cancel" id="">
                                    <b><?php echo genLabel('LBL_CANCAL') ?></b> 
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content" style="box-shadow: 1px 0px 10px #E5E5E5; background-color: #ffffff;">
                    <div id="bi" class="tab-pane active">
                        <div class="col-12">
                            <div class="card-body">
                                <div class="row">
                                    <div class="container demo col-sm-12">
                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default">
                                                <div class="panel-headingom panel-clrom active" role="tab" id="headingOne">
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
                                                                    <form class="form">
                                                                        <div class="form-group m-t-5 row">
                                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom"><?php echo genLabel('LBL_ORDER_NO') ?> </label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <input class="form-control form-controlom" type="text">
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <form class="form">
                                                                        <div class="form-group m-t-5 row">
                                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom"><?php echo genLabel('LBL_ORDER_DATE') ?></label>
                                                                            <div class="col-sm-9 col-sm-9om">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control form-controlom mydatepicker" placeholder="">
                                                                                    <div class="input-group-append" style="height: 25px;">
                                                                                     <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10">
                                                                                         
                                                                                     </i></span>
                                                                                 </div>
                                                                             </div>
                                                                             <small class="form-control-feedback">(dd-mm-yyyy)</small>
                                                                         </div>
                                                                     </div>
                                                                 </form>
                                                             </div>
                                                         </div>
                                                         <div class="row" style="margin-top: -40px;">
                                                             <div class="col-sm-6">
                                                                <form class="form">
                                                                    <div class="form-group row">
                                                                        <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom"><?php echo genLabel('LBL_ORDER_STATUS') ?></label>
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
                                                                </form>
                                                            </div>
                                                            <div class="col-sm-6">

                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: -25px;">
                                                            <div class="col-sm-6">
                                                                <form class="form">
                                                                    <div class="form-group row">
                                                                        <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                            <?php echo genLabel('LBL_ACCOUNT_NAME') ?>
                                                                        </label>
                                                                        <div class="col-sm-9 col-sm-9om">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control form-controlom" placeholder="">
                                                                                <div class="input-group-append" style="height: 25px;">
                                                                                   <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="10" height="10"></i></span>
                                                                                   <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="10" height="10"></i></span>
                                                                                   <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10"></i></span>
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
                                                                        <?php echo genLabel('LBL_VENDOR_HQ') ?>
                                                                    </label>
                                                                    <div class="col-sm-9 col-sm-9om">
                                                                        <input class="form-control form-controlom" type="text">
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
                                                                        <input class="form-control form-controlom" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row" style="margin-top: -30px;">
                                                                    <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                        <?php echo genLabel('LBL_TEL') ?>
                                                                    </label>
                                                                    <div class="col-sm-9 col-sm-9om">
                                                                        <input class="form-control form-controlom" type="text">
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
                                                                            <input type="text" class="form-control form-controlom" placeholder="">
                                                                            <div class="input-group-append" style="height: 25px;">
                                                                               <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/search.png'); ?>" width="10" height="10"></i></span>
                                                                               <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="10" height="10"></i></span>
                                                                               <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/calendar.png'); ?>" width="10" height="10"></i></span>
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
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <input class="form-control form-controlom" type="text">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-6">
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: -25px; margin-bottom: -25px;">
                                                    <div class="col-sm-6">
                                                        <form class="form">
                                                            <div class="form-group row">
                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                    <?php echo genLabel('LBL_VENDOR_SITE_CODE') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
                                                                </div>
                                                            </div>
                                                        </form>
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
                                                        <form class="form">
                                                            <div class="form-group m-t-5 row">
                                                                <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="margin-left: 8px;">
                                                                    <?php echo genLabel('LBL_NAME_OF_DELIVERY_LOCATION') ?>
                                                                </label>
                                                                <div class="col-sm" style="background-color: #ffffff; margin-left: 57px;">
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <?php echo genLabel('LBL_BUILDING_VILLAGE') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <form class="form">
                                                            <div class="form-group row">
                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                    <?php echo genLabel('LBL_ROOM_NO_FLOOR') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <?php echo genLabel('LBL_NO_DELIVERY') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <form class="form">
                                                            <div class="form-group row">
                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                    <?php echo genLabel('LBL_VILLAGE_NO') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <?php echo genLabel('LBL_ALLEY_LANE') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <form class="form">
                                                            <div class="form-group row">
                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                    <?php echo genLabel('LBL_ROAD') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <?php echo genLabel('LBL_SUB_DISTRICT') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <form class="form">
                                                            <div class="form-group row">
                                                                <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                    <?php echo genLabel('LBL_DISTRICT') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <input class="form-control form-controlom" type="text">
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
                                                                    <?php echo genLabel('LBL_PROVINCE') ?>
                                                                </label>
                                                                <div class="col-sm-9 col-sm-9om">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control form-controlom" placeholder="">
                                                                        <div class="input-group-append" style="height: 25px;">
                                                                         <span class="input-group-text" style="background-color: #ffffff; border: 0px;"><i><img src="<?php echo site_assets_url('images/icons/moreinfob.png'); ?>" width="10" height="10"></i></span>
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
                                                                <?php echo genLabel('LBL_POSTAL_CODE') ?>
                                                            </label>
                                                            <div class="col-sm-9 col-sm-9om">
                                                                <input class="form-control form-controlom" type="text">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px; margin-bottom: -25px;">
                                                <div class="col-sm-6">
                                                    <form class="form">
                                                        <div class="form-group row">
                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                <?php echo genLabel('LBL_TELEPHONE_DELIVERY') ?>
                                                            </label>
                                                            <div class="col-sm-9 col-sm-9om">
                                                                <input class="form-control form-controlom" type="text">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-sm-6">
                                                    <form class="form">
                                                        <div class="form-group row">
                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                <!-- <?php echo genLabel('LBL_FAX') ?> -->
                                                            </label>
                                                            <div class="col-sm-9 col-sm-9om">
                                                                <input class="form-control form-controlom" type="text">
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
                                                <div class="col-sm-6">
                                                    <form class="form">
                                                        <div class="form-group m-t-5 row">
                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                <?php echo genLabel('LBL_PRICE_SUBMISSION') ?>
                                                            </label>
                                                            <div class="col-sm-9 col-sm-9om">
                                                                <input class="form-control form-controlom" type="text">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-sm-6">
                                                    <form class="form">
                                                        <div class="form-group m-t-5 row">
                                                            <label for="example-text-input" class="col-sm control-label col-form-label col-form-labelom">
                                                                <?php echo genLabel('LBL_DEADLINE') ?>
                                                            </label>
                                                            <div class="col-sm-9 col-sm-9om">
                                                                <input class="form-control form-controlom" type="text">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: -25px;">
                                                <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="margin-left: 10px;">
                                                    <?php echo genLabel('LBL_RIGHR_TO_OFFER') ?>
                                                </label>
                                                <div class="col-sm" style="background-color: #ffffff; margin-left: 68px;">
                                                    <textarea class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="row m-b-5">
                                                <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="margin-left: 10px;">
                                                    <?php echo genLabel('LBL_TERMS_CONDITIONS') ?>
                                                </label>
                                                <div class="col-sm" style="background-color: #ffffff; margin-left: 23px;">
                                                    <textarea class="form-control" rows="3"></textarea>
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
                                                    <?php echo genLabel('LBL_DESCRIOTION') ?>
                                                </label>
                                                <div class="col-sm" style="background-color: #ffffff; margin-left: 10px;">
                                                    <textarea class="form-control" rows="3"></textarea>
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
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="margin-left: 10px;">
                                            &nbsp;<?php echo genLabel('LBL_ITEM_DETAILS') ?>
                                        </a>
                                        <div class="ml-auto item">
                                            <div class="row" style="padding-right: 10px;">
                                                <div class="form-group row" style="margin-bottom: 0;">
                                                    <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="padding-bottom: 0px; margin-top: 0px; padding-right: 0px;">Price Type</label>
                                                    <div class="col-sm" style="padding-right: 30px;">
                                                        <select class="form-control form-controlom" style="min-height: 0px; height: 30px; font-size: 11px;">
                                                            <option selected="">Exclude Vat</option>
                                                            <option value="1">Tomorrow</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="margin-bottom: 0;">
                                                    <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="padding-bottom: 0px; margin-top: 0px; padding-right: 0px;">Currency</label>
                                                    <div class="col-sm" style="padding-right: 30px;">
                                                        <select class="form-control form-controlom" style="min-height: 0px; height: 30px; font-size: 11px;">
                                                            <option selected="">Thailand,Bant (฿) </option>
                                                            <option value="1">Tomorrow</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="margin-bottom: 0;">
                                                    <label for="example-text-input" class="control-label col-form-label col-form-labelom" style="padding-bottom: 0px; margin-top: 0px; padding-right: 0px;">Tax Mode</label>
                                                    <div class="col-sm">
                                                        <select class="form-control form-controlom" style="min-height: 0px; height: 30px; font-size: 11px;">
                                                            <option selected="">Group</option>
                                                            <option value="1">Tomorrow</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapseFive" class="panel-collapse panel-collapsom panel-collapseitem collapse" role="tabpanel" aria-labelledby="headingFive">
                                <div class="panel-body panel-bodyom">
                                    <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                                        <div class="table-responsive text-center">
                                            <table class="table tableom" style="margin-bottom: 0px;">
                                                <thead style="font-size: 12px; color: #2B2B2B; font-family: PromptMedium;">
                                                    <tr>
                                                        <th>รายการสินค้า</th>
                                                        <th>Product Type</th>
                                                        <th>Km</th>
                                                        <th>Zone</th>
                                                        <th>ขนาดรถ</th>
                                                        <th>Plant ID</th>
                                                        <th>หน่วย</th>
                                                        <th>จำนวน</th>
                                                        <th>ราคา/หน่วย</th>
                                                        <th>LP</th>
                                                        <th>ส่วนลด</th>
                                                        <th>หักส่วน</th>
                                                        <th>จำนวนเงิน <br>(ซื้อ)</th>
                                                        <th>Min</th>
                                                        <th>DLV_C</th>
                                                        <th>DLV_C+VAT</th>
                                                        <th>DLV_P+VAT</th>
                                                        <th>จำนวนเงิน</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="border_bottom">
                                                        <td><input class="form-control form-controlom" type="text" value="คอนกรีตกำลังอัด 280 กก/ตร.ซม (Cube)" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; width:270px; padding-left: 5px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="Product" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 75px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="1123" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="1" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="xxx" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 45px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="1000990" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 60px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="ลบ.ม" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 45px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="11.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="2,270.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 60px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 30px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="0.26" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 65px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="3.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="350 คิว" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 50px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="37450/คิว" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 80px; background-color: #EDEDED;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="400/คิว" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 80px; background-color: #EDEDED;"></td>
                                                        <td style="background-color: #EDEDED; text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium; border-bottom: 1px solid #EDEDED;">24,970.00</td>
                                                    </tr>
                                                    <tr class="border_bottom">
                                                        <td><input class="form-control form-controlom" type="text" value="ค่าขนส่ง (ไม่เติมเที่ยว)" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; width:270px; padding-left: 5px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="Service" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 75px; background-color: #EDEDED;"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><input class="form-control form-controlom" type="text" value="รายการ" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 45px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="1.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="800.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 60px;"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="background-color: #EDEDED; text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium; border-bottom: 1px solid #EDEDED;">800.00</td>
                                                    </tr>
                                                    <tr class="border_bottom">
                                                        <td><input class="form-control form-controlom" type="text" value="ค่าบริการ ปั๊มลาก (ปริมาณคอนกรีตผ่านปั๊มไม่เกิน 50 ลบ.ม" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; width:270px; padding-left: 5px;"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><input class="form-control form-controlom" type="text" value="รายการ" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 45px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="1.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="12,900.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 60px;"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="background-color: #EDEDED; text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium; border-bottom: 1px solid #EDEDED;">12,900.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input class="form-control form-controlom" type="text" value="ค่าบริการ เก็บตัวอย่างก้อนปูน (Cube)" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; width:270px; padding-left: 5px;"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><input class="form-control form-controlom" type="text" value="รายการ" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 45px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="6.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 40px;"></td>
                                                        <td><input class="form-control form-controlom" type="text" value="642.00" style="font-size: 1px; color: #2B2B2B; font-family: PromptMedium; padding-left: 5px; width: 60px;"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="background-color: #EDEDED; text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium;">642.00</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="border_top">
                                                        <td colspan="16" style="text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium;">รวม</td>
                                                        <td colspan="2" style="text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium;">38,562.62</td>
                                                    </tr>
                                                    <tr class="border_top">
                                                        <td colspan="16" style="text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium;">ภาษีมูลค่าเพิ่ม 7%</td>
                                                        <td colspan="2" style="text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium;">2,699.38</td>
                                                    </tr>
                                                    <tr class="border_top" style="background-color: #F7F7F7;">
                                                        <td colspan="16" style="text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium;"><b>รวมทั้งสิ้น</b></td>
                                                        <td colspan="2" style="text-align: right; font-size: 1px; color: #2B2B2B; font-family: PromptMedium;">41,262.00</td>
                                                    </tr>
                                                </tfoot>
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
</div>
</div>
</div>
</div>


<style type="text/css">

    @font-face {
      font-family: PromptMedium;
      src: url(assets/fonts/Prompt-Medium.ttf);
  }

  .row1 {
    margin-top: 20px;
}

.page-wrapper {
    /*background: #DBDBDB;*/
    background-image: linear-gradient(180deg, #FFFFFF, #A9A9A9);
}

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #ffffff;
    background-color: #E97126;
    border-color: #E97126 #E97126 #E97126;
    font-family: PromptMedium;
    font-size: 16px;
    font-weight: 500;
}

.one {
    border-bottom: 0px solid #E97125;
}

.nav-tabs .one {
    border-bottom: 0px solid #E97125;
}

.nav-tabs {
    margin-top: -3px;
}

.nav-tabs .nav-link {
    color: #000;
    border-color: #E3E1DE;
    font-size: 16px;
    font-family: PromptMedium;
    font-weight: 500;
}

.nav-tabs .nav-link:hover {
    border-color:#e9ecef #e9ecef #dee2e6;
    border-bottom: 0px;
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
    color: #000000;
    border-color: #ffffff;
    box-shadow: 1px 0px 10px #E5E5E5;
    float: right;
    font-family: PromptMedium;
    font-size: 11px;
}

.btncallin:hover {
    background-color: #ffffff;
    color: #000000;
    border-color: #ffffff;
}

.btnsave {
    background-color: #ffffff;
    color: #E97126;
    /*border-color: #ffffff;*/
    box-shadow: 1px 0px 10px #E5E5E5;
    /*font-weight: bold;*/
    font-family: PromptMedium;
    font-size: 11px;
}

.btnsave:hover {
    color: #E97126;
    border-color: #ffffff;

}


.btnmoreinfo {
    background-color: #ffffff;
    color: #000000;
    border-color: #ffffff;
    box-shadow: 1px 0px 10px #E5E5E5;
}

.btnclear {
    background-color: #ffffff;
    color: #000000;
    border-color: #ffffff;
    box-shadow: 1px 0px 10px #E5E5E5;
}

.btnadvancesearch {
    background-color: #ffffff;
    color: #000000;
    border-color: #ffffff;
    box-shadow: 1px 0px 10px #E5E5E5;
}

    /*.btn-success:hover {
        color: #ffffff;
        background-color: #078BEA;
        border-color: #078BEA;
        }*/

.page-titles {
    margin: -20px -20px 15px -20px;
    padding: 0px;
    background: #EDEDED;
    padding-top: 0px;
    border-bottom: 3px solid #E97126;
}   

ul.list-inline li {
    background: #ffffff;
    padding: 5px;
    border-radius: 4px;
    white-space: nowrap;
    box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
}

ul.list-inline li a{
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
          background-color: rgb(0,0,0); /* Fallback color */
          background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
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
      color: #2B2B2B;
      float: right;
      font-size: 28px;
      font-weight: bold;
  }

  .close:hover,
  .close:focus {
      color: #2B2B2B;
      text-decoration: none;
      cursor: pointer;
  }

  /* The Close Button */
  .close2 {
      color: #2B2B2B;
      float: right;
      font-size: 28px;
      font-weight: bold;
  }

  .close2:hover,
  .close2:focus {
      color: #2B2B2B;
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
}

.form-control:disabled, .form-control[readonly] {
    background-color: #EDEDED;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    background-color: #EDEDED;
    height: 38px;
}

.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #e9ecef;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #2B2B2B transparent transparent transparent;
}

.input-group-text {
    background-color: #EDEDED;
}

.form-control::placeholder {
    color: #A9A9A9;
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
    line-height: 39px;
    font-family: PromptMedium;
    color: #2B2B2B;
    font-size: 11px;
}

.h4, h4 {
    font-family: PromptMedium;
}

.demo {
  padding-top: 10px;
  padding-bottom: 60px;
}

.panel-clr {
    background-color: #EDEDED;
    border-radius: 5px 5px 0px 0px;
}

.panel-clr.on {
    background-color: #FECFB1;
    border-radius: 5px 5px 0px 0px;
}

.panel-heading {
    padding: 0;
    border:0;
}
.panel-title>a, .panel-title>a:active{
    display:block;
    padding: 15px;
    color: #2B2B2B;
    font-size: 12px;
    font-family: PromptMedium;
    font-weight:bold;
    text-transform:none;
    letter-spacing:1px;
    word-spacing:3px;
    text-decoration:none;
}
.panel-heading  a:before {
 font-family: 'Glyphicons Halflings';
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

.panel-body{
  height:100%;
  background-color: #ffffff;
  border: 1px solid #EDEDED;
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
    background-color: #F7F7F7;
}

.formsearching {
    min-height: 5px;
}

.colsm12searching {
    padding-left: 5px;
    padding-left: 5px;
    padding-bottom: 5px;
}

.colsm6searching {
    border: 1px solid #EDEDED;
    padding: 0px;
}

.btnsavesearching {
    color: #E97126;
    font-family: PromptMedium;
    font-size: 11px;
    background-color: #ffffff;
    float: right;
    border-color: #ffffff;
    box-shadow: 1px 0px 10px #E5E5E5;
}

.btnsavesearching:hover {
    color: #E97126;
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
    background-color: #018FFB;
    font-size: 11px;
    font-family: PromptMedium;
    box-shadow: 1px 0px 10px #E5E5E5;
}

.cancel {
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 2px;
    background-color: #FFFFFF;
    color: #FEB018;
    font-size: 11px;
    font-family: PromptMedium;
    box-shadow: 1px 0px 10px #E5E5E5;
}

.panel-clrom {
    background-color: #EDEDED;
    border: 5px 5px 0px 0px;
}

.panel-clrom.on {
    background-color: #EDEDED;
    border: 5px 5px 0px 0px;
}

.panel-headingom  a:before {
 font-family: 'Glyphicons Halflings';
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

.panel-bodyom{
  height:100%;
  background-color: #F7F7F7;
  border: 1px solid #EDEDED;
  /*overflow: auto;*/
  border-radius: 0px 0px 5px 5px;
}

.col-form-labelom {
    font-size: 11px;
    color: #2B2B2B;
}

.col-sm-9om {
    background-color: #ffffff;
}

.form-controlom {
    min-height:25px;
    height: 0px;
    padding: 0px;
}

.idhead a{
    color: #2B2B2B;
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

.table.tableom td, .table.tableom th {
    padding: 5px;
    vertical-align: unset;

}

.table.tableom th, .table.tableom thead th {
    border: 1px solid #EDEDED;
    background-color: #F7F7F7;
}

.table.tableom tbody {
    background-color: #ffffff;
    font-size: 11px;
    font-family: PromptMedium;
    color: #2B2B2B;
}

tr.border_bottom td {
  border-bottom: 1px solid #ffffff;
}

tr.border_top td {
  border: 1px solid #EDEDED;
}

.table.tableom tfoot {
    background-color: #ffffff;

}

.input-group-append .btn, .input-group-prepend .btn {
    z-index: unset;
}

.k-editor, .k-grid, .k-menu, .k-scheduler {
    height: 400px;
}

.disabled{
    pointer-events: none;
    cursor: default;
    text-decoration: none;
    color: black;
    opacity: 0.5;
}

</style>

<script>

    // var modalAdsearch = document.getElementById("modalAdsearch");

    // var btnadsearch = document.getElementById("adsearch");

    // var p = document.getElementsByClassName("close2")[0];

    // btnadsearch.onclick = function() {
    //   modalAdsearch.style.display = "block";
    // }    

    // When the user clicks on <span> (x), close the modal
    // p.onclick = function() {
    //   modalAdsearch.style.display = "none";
    // }

    // window.onclick = function(event) {
    //   if (event.target == modalAdsearch) {
    //     modalAdsearch.style.display = "none";
    //   }
    // }

    var modalAccountsearch = document.getElementById("modalAccountsearch");

    var btnadsearch = document.getElementById("adsearch");

    var closeAccountsearch = document.getElementById("closeAccountsearch");

    btnadsearch.onclick = function() {
        modalAccountsearch.style.display = "block";
    }

    closeAccountsearch.onclick = function() {
        modalAccountsearch.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == modalAccountsearch) {
        modalAccountsearch.style.display = "none";
    }
}

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

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
      modal.style.display = "block";

      var url ="<?php echo site_url('home/getplant'); ?>";

      $.ajax(url, {
         type: 'POST',
         data: "",
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result.data);
          if(result['Type'] =='S'){
            console.log("success");
        }else{
            console.log("fall");
        }

    },
    error: function (data){
      console.log("f");
        }
    });

   }

    // When the user clicks on <span> (x), close the modal
    closesearching.onclick = function() {
      modal.style.display = "none";
  }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
    }
}

var myModal = document.getElementById("myModal");

var savesearchingbtn = document.getElementById("savesearchingbtn");

var closeordermanagement = document.getElementById("closeordermanagement");

savesearchingbtn.onclick = function() {
    modal.style.display = "none";
    if (modal.style.display == "none") {
        myModal.style.display = "block";
    }
}

closeordermanagement.onclick = function() {
    myModal.style.display = "none";
}


    // var modalAdsearch = document.getElementById("modalAdsearch");

    // var btnadsearch = document.getElementById("adsearch");

    // btnadsearch.onclick = function() {
    //     modalAdsearch.style.display = "block";
    // }

    // // When the user clicks on <span> (x), close the modal
    // span.onclick = function() {
    //   modalAdsearch.style.display = "none";
    // }

    // // When the user clicks anywhere outside of the modal, close it
    // window.onclick = function(event) {
    //   if (event.target == modalAdsearch) {
    //     modalAdsearch.style.display = "none";
    //   }
    // }



    function myMap() {
        var mapProp= {
          center:new google.maps.LatLng(51.508742,-0.120850),
          zoom:5,
      };
      var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
  }

    // Date Picker
    jQuery('.mydatepicker, #datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });

    jQuery('#date-range').datepicker({
        toggleActive: true
    });

    // For select 2
    $(".select2").select2();
    //$('.selectpicker').selectpicker();

    // Daterange picker
    $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-daterange-timepicker').daterangepicker({
        timePicker: true,
        format: 'MM/DD/YYYY h:mm A',
        timePickerIncrement: 30,
        timePicker12Hour: true,
        timePickerSeconds: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-limit-datepicker').daterangepicker({
        format: 'MM/DD/YYYY',
        minDate: '06/01/2015',
        maxDate: '06/30/2015',
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        dateLimit: {
            days: 6
        }
    });

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx1XaB5SWwkowhESNVjWoDhjVFPKyGt7Q&callback=myMap">

</script>

<script type="text/javascript">

  $( function() {
        $('.panel-clr').click( function() {
          $(this).toggleClass('on');
        })

        var datasource = {
            pageSize: 10,
            filter:{
                logic:"and",
                filters: []
            },
            schema: {
                model: {
                    id: ""
                },
                data: "data",
                total: "pagesize"
            },

            serverPaging: false,
            serverFiltering: false,
            serverSorting: false,
            height: 550,
        }
        var columns = [
            {
                field: "phone",
                title : "หมายเลขโทรศัพท์",
                width: 100
            }, {
                field: "accountname",
                title : "ชื่อ - นามสกุล",
                width: 100
            }, {
                field: "account_no",
                title: "Contact No",
                width: 100
            }, {
                field: "accounting_no",
                title: "Line ID",
                width: 100
            }, {
                field: "email1",
                title: "Facebook",
                width: 100
            
            }
        ];
        
        // $('#grid').genKendoGrid(datasource,columns);
        $('#grid').genKendoGridPopup(datasource,columns);

        var datasourcefaq ={
            pageSize: 10,
            filter:{
                logic:"and",
                filters: []
            },
            schema: {
                model: {
                    id: ""
                },
                data: "data",
                total: "pagesize"
            },
            
            serverPaging: false,
            serverFiltering: false,
            serverSorting: false
        }

        var columnsfaq = [
            {
                field: "faqstatus",
                title : "<?php echo genLabel('LBL_MORE_INFO') ?>",
                width: 100
            }, {
                field: "setype",
                title : "<?php echo genLabel('LBL_FAQ_TYPE') ?>",
                width: 100
            }, {
                field: "faq_name",
                title: "<?php echo genLabel('LBL_FAQ_NAME') ?>",
                width: 100
            }, {
                field: "faqcategories",
                title: "<?php echo genLabel('LBL_FAQ_COUNT') ?>",
                width: 100
            }
        ];

        $('#grid_faq').genKendoGrid(datasourcefaq,columnsfaq);

        var formDatafaq = $('#form_faq').serialize();
        var urlfaq ="<?php echo site_url('home/getfaq'); ?>";

            $.ajax(urlfaq, {
             type: 'POST',
             data: formDatafaq,
             success: function (data){
              var result = jQuery.parseJSON(data);
              console.log(result.data);
              if(result['Type'] =='S'){
                
                $("#grid_faq").data('kendoGrid').dataSource.data([]);
                var gridfaq = $('#grid_faq').data('kendoGrid');
                $.each(result.data, function( key, value ) {
                  gridfaq.dataSource.add({faqstatus:value.faqstatus,setype:value.setype,faq_name:value.faq_name,faqcategories:value.faqcategories});
                });

              }else{
                $("#grid_faq").data('kendoGrid').dataSource.data([]);
              }
              
             },
             error: function (data){
              console.log("f");
             }
          });

        $(document).on('click','.fillterfaq',function(){
            var formDatafaq = $('#form_faq').serialize();
            var urlfaq ="<?php echo site_url('home/getfaq'); ?>";

            $.ajax(urlfaq, {
               type: 'POST',
               data: formDatafaq,
               success: function (data){
                  var result = jQuery.parseJSON(data);
                  console.log(result.data);
                  if(result['Type'] =='S'){

                    $("#grid_faq").data('kendoGrid').dataSource.data([]);
                    var gridfaq = $('#grid_faq').data('kendoGrid');
                    $.each(result.data, function( key, value ) {
                      gridfaq.dataSource.add({faqstatus:value.faqstatus,setype:value.setype,faq_name:value.faq_name,faqcategories:value.faqcategories});
                  });

                }else{
                    $("#grid_faq").data('kendoGrid').dataSource.data([]);
                }

            },
            error: function (data){
              console.log("f");
          }
      });
        });

        var datasourcekm ={
            pageSize: 10,
            filter:{
                logic:"and",
                filters: []
            },
            schema: {
                model: {
                    id: ""
                },
                data: "data",
                total: "pagesize"
            },
            
            serverPaging: false,
            serverFiltering: false,
            serverSorting: false
        }

        var columnskm = [
            {
                field: "knowledgebaseid",
                title : " ",
                width: 100
            }, {
                field: "know_remark",
                title : "เพิ่มเติม",
                width: 100
            }, {
                field: "knowledgebase_no",
                title: "หมายเลข",
                width: 100
            }, {
                field: "knowledgebase_name",
                title: "รายละเอียดองค์ความรู้",
                width: 100
            }, {
                field: "know_category",
                title: "ประเภทองค์ความรู้",
                width: 100
            }, {
                field: "presence",
                title: "จำนวนเข้าชม",
                width: 100
            }, {
                field: "status",
                title: "อ้างอิง",
                width: 100
            }
        ];

        $('#grid_km').genKendoGrid(datasourcekm,columnskm);

        var formDatakm = $('#form_km').serialize();
        var urlkm = "<?php echo site_url('home/getkm'); ?>";

        $.ajax(urlkm, {
             type: 'POST',
             data: formDatakm,
             success: function (data){
              var result = jQuery.parseJSON(data);
              console.log(result.data);
              if(result['Type'] =='S'){
                
                $("#grid_km").data('kendoGrid').dataSource.data([]);
                var gridkm = $('#grid_km').data('kendoGrid');
                $.each(result.data, function( key, value ) {
                  gridkm.dataSource.add({knowledgebaseid:value.knowledgebaseid,know_remark:value.know_remark,knowledgebase_no:value.knowledgebase_no,knowledgebase_name:value.knowledgebase_name,know_category:value.know_category,presence:value.presence,status:value.status});
                });

              }else{
                $("#grid_km").data('kendoGrid').dataSource.data([]);
              }
              
             },
             error: function (data){
              console.log("f");
             }
          });

        $(document).on('click','.fillterkm',function(){
            var formDatakm = $('#form_km').serialize();
            var urlkm = "<?php echo site_url('home/getkm'); ?>";

            $.ajax(urlkm, {
               type: 'POST',
               data: formDatakm,
               success: function (data){
                  var result = jQuery.parseJSON(data);
                  console.log(result.data);
                  if(result['Type'] =='S'){

                    $("#grid_km").data('kendoGrid').dataSource.data([]);
                    var gridkm = $('#grid_km').data('kendoGrid');
                    $.each(result.data, function( key, value ) {
                      gridkm.dataSource.add({knowledgebaseid:value.knowledgebaseid,know_remark:value.know_remark,knowledgebase_no:value.knowledgebase_no,knowledgebase_name:value.knowledgebase_name,know_category:value.know_category,presence:value.presence,status:value.status});
                  });

                }else{
                    $("#grid_km").data('kendoGrid').dataSource.data([]);
                }

            },
            error: function (data){
              console.log("f");
          }
      });
        });



        var formData = $('#form_account').serialize();
        var url ="<?php echo site_url('home/getaccount'); ?>";
        
        $.ajax(url, {
           type: 'POST',
           data: formData,
           success: function (data){
              var result = jQuery.parseJSON(data);
              console.log(result.data);
              if(result['Type'] =='S'){

                $("#grid").data('kendoGrid').dataSource.data([]);
                var grid = $('#grid').data('kendoGrid');
                $.each(result.data, function( key, value ) {
                  grid.dataSource.add({phone:value.phone,accountname:value.accountname,account_no:value.account_no,accounting_no:value.accounting_no,email1:value.email1,accountid:value.accountid,lineid:value.line_id,facebook:value.facebook,firstname:value.first_name,lastname:value.last_name,modifiedtime:value.modifiedtime,remark:value.remark,taxpayeridentificationno:value.taxpayer_identification_no_bill_to,address:value.address,billtoaddress:value.bill_to_address});
                });

            }else{
                $("#grid").data('kendoGrid').dataSource.data([]);
            }

        },
        error: function (data){
          console.log("f");
      }
  });

        $(document).on('click','.fillter',function(){
            var formData = $('#form_account').serialize();
            var url ="<?php echo site_url('home/getaccount'); ?>";

            console.log(formData);

            $.ajax(url, {
             type: 'POST',
             data: formData,
             success: function (data){
              var result = jQuery.parseJSON(data);
              console.log(result.data);
              if(result['Type'] =='S'){
                
                $("#grid").data('kendoGrid').dataSource.data([]);
                var grid = $('#grid').data('kendoGrid');
                $.each(result.data, function( key, value ) {
                  grid.dataSource.add({phone:value.phone,accountname:value.accountname,account_no:value.account_no,accounting_no:value.accounting_no,email1:value.email1,accountid:value.accountid,lineid:value.line_id,facebook:value.facebook,firstname:value.first_name,lastname:value.last_name,modifiedtime:value.modifiedtime,remark:value.remark,taxpayeridentificationno:value.taxpayer_identification_no_bill_to,address:value.address,billtoaddress:value.bill_to_address});
                });

              }else{
                $("#grid").data('kendoGrid').dataSource.data([]);
              }
              
             },
             error: function (data){
              console.log("f");
             }
          });
        });


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
            $('#contact_code').val("");
            $('#status_contact').val("").trigger('change');
            $('#title_name').val("").trigger('change');
            $('#fristname_contact').val("");
            $('#lastname_contact').val("");
            $('#phone_contact').val("");
            $('#email_contact').val("");
            $('#lineid_contact').val("");
            $('#facebook_contact').val("");
            $('#remark_contact').val("");
            $('#natured').prop("checked", false);
            $('#normal').prop("checked", false);
            $('#morose').prop("checked", false);
            $('#sitecode_contact').val("");
            $('#dateupdate_contact').val("");
        }

        $.clearAllBill = function() {
            $('#companyname').val("");
            $('#brach').val("");
            $('#taxno').val("");
            $('#telephone').val("");
            $('#address').val("");
            $('#contact_person').val("");
            $('#contact_tel').val("");
            $('#address_bill_to').val("");
        }

        $.clearAllCase = function() {
            $('#caseid').val("");
            $('#problem_name').val("").trigger('change');
            $('#shotcut_name').val("").trigger('change');
            $('#status_case').val("").trigger('change');
            $('#contact_channel_case').val("").trigger('change');
            $('#response_case').val("").trigger('change');
            $('#assign_to_case').val("");
            $('#due_date_case').val("");
            $('#close_date_case').val("");
            $('#detail_case').val("");
            $('#note_case').val("");
            $('#cartor_case').val("");
            $('#date_create').val("");
        }

  }); 

  $('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
});

  $('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
});

  $( function() {
    $('.panel-clrom').click( function() {
      $(this).toggleClass('on');
  })
}); 

  $('.panel-collapseom').on('show.bs.collapse', function () {
    $(this).siblings('.panel-headingom').addClass('active');
});

  $('.panel-collapseom').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-headingom').removeClass('active');
});

  $('.panel-collapseitem').on('show.bs.collapse', function () {
    $('.item').show();
});

  $('.panel-collapseitem').on('hide.bs.collapse', function () {
    $('.item').hide();
});

</script>