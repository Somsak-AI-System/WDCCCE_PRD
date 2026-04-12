<?php
global $site_URL;
?>
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

<div class="bg-popup"></div>
<div id="popup_users" class="">
    <div id="sidebarcontents">            
        <div class="scrollbar" style="padding: 2px;height: 400px; overflow-x: hidden;overflow-y: auto;">         
            <div class="row" style="margin-top: 5px;">
                <div class="col-12"> 

                    <div class="col-12" id="usersUL">
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</div>

<div id="popup_field" class="">
    <div id="sidebarcontents">            
        <div class="scrollbar" style="padding: 2px;height: 400px; overflow-x: hidden;overflow-y: auto;">        
            
            <div class="col-sm-12" id="fieldUL">
            </div>

        </div>
    </div>
</div>

<!-- Page Content -->
<div class="page-wrapper page-content">
    <div class="container-fluid">
        <ul class="nav mb-20" role="tablist"><!-- nav-tabs -->
            <li class="nav-item"> 
                <a class="nav-link active" data-toggle="tab" href="#summary" role="tab">
                    <span class="hidden-xs-down">Summary</span>
                </a> 
            </li>
            <li class="nav-item"> 
                <a class="nav-link" id="tab-detail" data-toggle="tab" href="#detail" role="tab">
                    <span class="hidden-xs-down">Detail</span>
                </a> 
            </li>
            <li class="nav-item"> 
                <a class="nav-link" id="tab-related" data-toggle="tab" href="#related" role="tab">
                    <span class="hidden-xs-down">Related</span>
                </a> 
            </li>
            <li class="nav-item"> 
                <a class="nav-link" id="tab-timeline" data-toggle="tab" href="#timeline" role="tab">
                    <span class="hidden-xs-down">Timeline</span>
                </a>
            </li>    
        </ul>

        <input type="hidden" name="projectorder_status" id="projectorder_status" value="<? echo @$status[0]['projectorder_status']; ?>">
        <select class="select-satus float-right mx-5" id="select-satus" style="">
            <? foreach (@$picklist_status as $key => $value) {
                $selected = ($status[0]['projectorder_status'] == $value['projectorder_status']) ? "selected" : "";
                echo '<option value="'.$value['projectorder_status'].'" '.$selected.' >'.$value['projectorder_status'].'</option>';
            } ?>
        </select>

        <div class="btn_report" role="button" title="Report" onclick="window.open('<?php echo $report_viewer_url;?>rpt_competitor_product.rptdesign&projectsid=<? echo $crmID; ?>&__format=pdf', '_blank');" style="position: absolute;right: 160px;top: 10px;">
            <i class="ph-light ph-file-text icon-detail"></i>
        </div>
        <?php if($display == 'yes'){ ?>
        <div class="btn_edit" role="button" title="Edit" onclick="$.editRecord()" style="position: absolute;right: 110px;top: 10px;">
            <i class="ph-light ph-note-pencil icon-detail"></i>
        </div>
        <div class="btn_dup" role="button" title="Duplicate" onclick="$.duplicate()" style="position: absolute;right: 60px;top: 10px;">
            <i class="ph-light ph-copy icon-detail"></i>
        </div>
        <div class="btn_del" role="button" title="Delete" onclick="$.deleteRecord()" style="position: absolute;right: 10px;top: 10px;">
            <i class="ph-light ph-trash icon-detail"></i>
        </div>
        <?php } ?>

        <div class="tab-content tabcontent-border">
            <!-- Summary -->
            <div class="tab-pane tab-all active" id="summary" role="tabpanel">
                
                <div class="row">
                    <div class="col-4 tab-suummary" >
                        <div class="card-box mb-10">
                            
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Projects Information </div>
                                <div class="card-box-action flex-none"></div>
                            </div>

                            <div class="collapse show" id="box_summary" style="">
                                <div class="card-box-body" id="detail-summary">
                        
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 tab-suummary" >
                        <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Comment </div>
                                <div class="card-box-action flex-none"></div>
                            </div>
                            <div class="collapse show" id="box_comment" style="">
                                <div class="card-box-body">
                                    
                                    <div class="row mb-10">
                                        <div class="col-11">
                                            <div class="base-input-group bg-white" id="box-comment">
                                                <input type="text" class="base-input-text bg-white input-popup-search" id="message-comment" placeholder="Write a comment...">
                                            </div>
                                        </div>
                                        <div class="col-1" style="padding-right: unset !important; padding-left: unset !important;margin: auto;">
                                            <button type="button" class="btn_add_comment" id="btn_add_comment" onclick="$.addComments()">
                                                <i class="ph-paper-plane-tilt icon-comment"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row" id="list_comment"></div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 tab-suummary" >
                        
                        <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Sales Visit </div>
                                <div class="flex-none">
                                    <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Calendar" onclick="$.AddRelated(this)">
                                        <i class="ph-light ph-plus icon-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="summary-visit">
                                
                            </div>
                        </div>

                        <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Quotation </div>
                                <div class="flex-none">
                                    <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Quotes" onclick="$.AddRelated(this)">
                                        <i class="ph-light ph-plus icon-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="summary-quotation">
                                
                            </div>
                        </div>

                        <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Documents </div>
                                <div class="flex-none">
                                    <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Documents" onclick="$.AddRelated(this)">
                                        <i class="ph-light ph-plus icon-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="summary-documents">
                                
                            </div>
                        </div>

                        <!-- <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Price List </div>
                                <div class="flex-none">
                                    <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="PriceList" onclick="$.AddRelated(this)">
                                        <i class="ph-light ph-plus icon-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="summary-pricelist">
                                
                            </div>
                        </div> -->

                        <!-- <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Sample Requisition </div>
                                <div class="flex-none">
                                    <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Samplerequisition" onclick="$.AddRelated(this)">
                                        <i class="ph-light ph-plus icon-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="summary-samplerequisition">
                                
                            </div>
                        </div> -->

                        <!-- <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Expenses </div>
                                <div class="flex-none">
                                    <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Expense" onclick="$.AddRelated(this)">
                                        <i class="ph-light ph-plus icon-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="summary-expenses">
                                
                            </div>
                        </div> -->

                        <!-- <div class="card-box mb-10">
                            <div class="card-box-header-web flex">
                                <div class="card-box-title flex-1"> Questionnaire </div>
                                <div class="flex-none">
                                    <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Questionnaire" onclick="$.AddRelated(this)">
                                        <i class="ph-light ph-plus icon-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="summary-questionnaire">
                                
                            </div>
                        </div> -->
                    </div>
                </div>

            </div>
            
            <!-- Detail -->
            <div class="tab-pane tab-all" id="detail" role="tabpanel" >
                
                <div class="card-box mb-10" id="block_name_info" style="">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Projects Information </div>
                        <div class="card-box-action flex-none">
                            <div data-bs-toggle="collapse" href="#box0" role="button" aria-expanded="false">
                                <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="box0">
                        <div class="card-box-body">
                            <div class="row mb-5" id="projects-info"></div>
                        </div>
                    </div>
                </div>

                <div class="card-box mb-10" id="block_name_customer" >
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1">
                            Customer Information
                        </div>
                        <div class="card-box-action flex-none">
                            <div data-bs-toggle="collapse" href="#block_customer" role="button" aria-expanded="false">
                                <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show" id="block_customer">
                        <div class="card-box-body">
                            <!-- Designer -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Designer</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabDesigner" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px;">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 w-300px">หมายเลขผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ออกแบบโครงการ (ไทย)</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ออกแบบโครงการ (EN)</td>
                                            <td class="pd-10 w-300px">กลุ่มผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 w-300px">ประเภทธุรกิจผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 w-300px">ระดับผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ติดต่อผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 w-300px">Service Level</td>
                                            <td class="pd-10 w-300px">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5 w-200px">% Com. Sales</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Designer -->

                            <!-- Architecture -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Architecture</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabArchitec" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px;">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-300px">ชื่อสถาปนิกโครงการ</td>
                                            <td class="pd-10 w-300px">หมายเลขสถาปนิกโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อสถาปนิกโครงการ (ไทย)</td>
                                            <td class="pd-10 w-300px">ชื่อสถาปนิกโครงการ (EN)</td>
                                            <td class="pd-10 w-300px">กลุ่มสถาปนิกโครงการ</td>
                                            <td class="pd-10 w-300px">ประเภทธุรกิจสถาปนิกโครงการ</td>
                                            <td class="pd-10 w-300px">ระดับสถาปนิกโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ติดต่อสถาปนิกโครงการ</td>
                                            <td class="pd-10 w-300px">Service Level</td>
                                            <td class="pd-10 w-300px">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5 w-200px">% Com. Sales</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Architecture -->

                            <!-- Owner/Developer -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Owner/Developer</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabOwner" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px;">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-300px">ชื่อเจ้าของโครงการ</td>
                                            <td class="pd-10 w-300px">หมายเลขเจ้าของโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อเจ้าของโครงการ (ไทย)</td>
                                            <td class="pd-10 w-300px">ชื่อเจ้าของโครงการ (EN)</td>
                                            <td class="pd-10 w-300px">กลุ่มเจ้าของโครงการ</td>
                                            <td class="pd-10 w-300px">ประเภทธุรกิจเจ้าของโครงการ</td>
                                            <td class="pd-10 w-300px">ระดับเจ้าของโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ติดต่อเจ้าของโครงการ</td>
                                            <td class="pd-10 w-300px">Service Level</td>
                                            <td class="pd-10 w-300px">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5 w-200px">% Com. Sales</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Owner/Developer -->

                            <!-- Consultant -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Consultant</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabConsultant" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px;">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-300px">ชื่อที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 w-300px">หมายเลขที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อที่ปรึกษาโครงการ (ไทย)</td>
                                            <td class="pd-10 w-300px">ชื่อที่ปรึกษาโครงการ (EN)</td>
                                            <td class="pd-10 w-300px">กลุ่มที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 w-300px">ประเภทธุรกิจที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 w-300px">ระดับที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ติดต่อที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 w-300px">Service Level</td>
                                            <td class="pd-10 w-300px">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5 w-200px">% Com. Sales</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Consultant -->

                            <!-- Construction -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Construction</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabConstruction" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px;">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-300px">ชื่อช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 w-300px">หมายเลขช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อช่างก่อสร้างโครงการ (ไทย)</td>
                                            <td class="pd-10 w-300px">ชื่อช่างก่อสร้างโครงการ (EN)</td>
                                            <td class="pd-10 w-300px">กลุ่มช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 w-300px">ประเภทธุรกิจช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 w-300px">ระดับช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ติดต่อช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 w-300px">Service Level</td>
                                            <td class="pd-10 w-300px">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5 w-200px">% Com. Sales</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Construction -->

                            <!-- Contractor -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Contractor</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabContractor" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px;">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-300px">ชื่อผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 w-300px">หมายเลขผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้รับเหมาโครงการ (ไทย)</td>
                                            <td class="pd-10 w-300px">ชื่อผู้รับเหมาโครงการ (EN)</td>
                                            <td class="pd-10 w-300px">กลุ่มผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 w-300px">ประเภทธุรกิจผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 w-300px">ระดับผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 w-300px">ชื่อผู้ติดต่อผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 w-300px">Service Level</td>
                                            <td class="pd-10 w-300px">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5 w-200px">% Com. Sales</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Contractor -->

                            <!-- Landscape -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Landscape</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabSubContractor" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px;">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 ">ชื่อออกแบบภูมิทัศน์โครงการ</td>
                                            <td class="pd-10 ">หมายเลขออกแบบภูมิทัศน์โครงการ</td>
                                            <td class="pd-10 ">ชื่อออกแบบภูมิทัศน์ (ไทย)</td>
                                            <td class="pd-10 ">ชื่อออกแบบภูมิทัศน์ (EN)</td>
                                            <td class="pd-10 ">กลุ่มออกแบบภูมิทัศน์โครงการ</td>
                                            <td class="pd-10 ">ประเภทธุรกิจผู้ออกแบบภูมิทัศน์โครงการ</td>
                                            <td class="pd-10 ">ระดับผู้ออกแบบภูมิทัศน์โครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ติดต่อผู้ออกแบบภูมิทัศน์โครงการ</td>
                                            <td class="pd-10 ">Service Level</td>
                                            <td class="pd-10 ">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5">% Com. Sales</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <!-- Sub Contractor -->

                        </div>
                    </div>
                </div>

                <div class="card-box mb-10" id="block_name_key_man" style="">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Key Man Customer Information </div>
                        <div class="card-box-action flex-none">
                            <div data-bs-toggle="collapse" href="#boxkeyman" role="button" aria-expanded="false">
                                <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="boxkeyman">
                        <div class="card-box-body">
                            <div class="row mb-5" id="key-man"></div>
                        </div>
                    </div>
                </div>  

                <!-- Product Information -->
                <div class="card-box mb-10" id="block_name_product" >
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1">
                            Product Information
                        </div>
                        <div class="card-box-action flex-none">
                            <div style="display:flex; align-items:center; gap:4px">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    id="btn-recalculate"
                                    onclick="$.refreshSpecialPrice('<? echo $crmID; ?>')"
                                >
                                    Recalculate
                                </button>
                                <div data-bs-toggle="collapse" href="#boxproduct" role="button" aria-expanded="false">
                                    <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="collapse show" id="boxproduct">
                        <div class="card-box-body">
                            <div class="mb-5">

                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTab" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px">
                                    <thead>
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-400px">Product items</td>
                                            <td class="pd-10 w-300px">Brand</td>
                                            <td class="pd-10 w-300px">Product Group</td>
                                            <td class="pd-10 w-300px">Dealer</td>
                                            <td class="pd-10 w-200px">Create Act.</td>
                                            <td class="pd-10 w-300px">First Delivered</td>
                                            <td class="pd-10 w-300px">Last Delivered</td>
                                            <td class="pd-10 w-100px">Est</td>
                                            <td class="pd-10 w-100px">Plan</td>
                                            <td class="pd-10 w-100px">Deli.</td>
                                            <td class="pd-10 w-100px">On Hand</td>
                                            <td class="pd-10 w-300px border-bottom-radius-5">Sell Price</td>
                                            <td class="pd-10 w-300px border-bottom-radius-5">Total Amount (On Hand)</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>    
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Information -->

                <!-- Competitor Product Information -->
                <div class="card-box mb-10" id="block_name_competitor" >
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1">
                            Competitor Product Information
                        </div>
                        <div class="card-box-action flex-none">
                            <div data-bs-toggle="collapse" href="#boxcompetitor" role="button" aria-expanded="false">
                                <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show" id="boxcompetitor">
                        <div class="card-box-body">
                            <div class="mb-5">
                                
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabCom" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 1700px">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 w-400px">Com. Product items</td>
                                            <td class="pd-10 w-200px">Com. Brand</td>
                                            <td class="pd-10 w-200px">Com. Product Group</td>
                                            <td class="pd-10 w-200px">Com. Product Size</td>
                                            <td class="pd-10 w-200px">Com. Product Thickness</td>
                                            <td class="pd-10 w-200px">Com. Estimated unit</td>
                                            <td class="pd-10 w-200px">Com. Price</td>
                                        </tr>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Competitor Product Information -->

                <div class="card-box mb-10" id="block_name_remark" style="">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Remark </div>
                        <div class="card-box-action flex-none">
                            <div data-bs-toggle="collapse" href="#boxremark" role="button" aria-expanded="false">
                                <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="boxremark">
                        <div class="card-box-body">
                            <div class="row mb-5" id="remark"></div>
                        </div>
                    </div>
                </div> 

                <div class="card-box mb-10" id="block_name_remark" style="">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Administrator Information </div>
                        <div class="card-box-action flex-none">
                            <div data-bs-toggle="collapse" href="#boxadmin" role="button" aria-expanded="false">
                                <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                            </div>
                        </div>
                    </div>

                    <div class="collapse show" id="boxadmin">
                        <div class="card-box-body">
                            <div class="row mb-5" id="administrator"></div>
                        </div>
                    </div>
                </div> 
            </div>
            
            <!-- Related -->
            <div class="tab-pane tab-all" id="related" role="tabpanel" >
                <!-- Visit -->
                <div class="card-box mb-10">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Sales Visit </div>
                        <div class="flex-none">
                            <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Calendar" onclick="$.AddRelated(this)">
                                <i class="ph-light ph-plus icon-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div id="related-visit"></div>
                </div>
                <!-- Quotation -->
                <div class="card-box mb-10">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Quotation </div>
                        <div class="flex-none">
                            <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Quotes" onclick="$.AddRelated(this)">
                                <i class="ph-light ph-plus icon-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div id="related-quotation"></div>
                </div>
                <!-- Documents -->
                <div class="card-box mb-10">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Documents </div>
                        <div class="flex-none">
                            <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Documents" onclick="$.AddRelated(this)">
                                <i class="ph-light ph-plus icon-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div id="related-documents"></div>
                </div>
                <!-- Price List -->
                <!-- <div class="card-box mb-10">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Price List </div>
                        <div class="flex-none">
                            <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="PriceList" onclick="$.AddRelated(this)">
                                <i class="ph-light ph-plus icon-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div id="related-pricelist"></div>
                </div> -->
                <!-- Sample Requisition -->
                <!-- <div class="card-box mb-10">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Sample Requisition </div>
                        <div class="flex-none">
                            <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Samplerequisition" onclick="$.AddRelated(this)">
                                <i class="ph-light ph-plus icon-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div id="related-samplerequisition"></div>
                </div> -->
                <!-- Expenses -->
                <!-- <div class="card-box mb-10">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Expenses </div>
                        <div class="flex-none">
                            <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Expense" onclick="$.AddRelated(this)">
                                <i class="ph-light ph-plus icon-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div id="related-expenses"></div>
                </div> -->
                <!-- Questionnaire -->
                <!-- <div class="card-box mb-10">
                    <div class="card-box-header-web flex">
                        <div class="card-box-title flex-1"> Questionnaire </div>
                        <div class="flex-none">
                            <div class="btn-add-relate" role="button" data-return_module="Projects" data-crmid="<? echo $crmID; ?>" data-module="Questionnaire" onclick="$.AddRelated(this)">
                                <i class="ph-light ph-plus icon-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div id="related-questionnaire"></div>
                </div> -->

            </div>
            
            <!-- Timeline -->
            <div class="tab-pane" id="timeline" role="tabpanel">
                <div class="row">
                    <div class="col-12 float-right">
                        
                        <button class="select-fillter-assigto float-right mx-5" id="project_type" style="width: auto;" onclick="PopupUsers();">ผู้รับผิดชอบ <i class="ph-light ph-caret-down" style="font-size: 16px;vertical-align: middle;"></i></button>

                        <button class="select-fillter-field float-right mx-5" id="project_type" style="width: auto;" onclick="PopupField();">ช่องกรอง <i class="ph-light ph-caret-down" style="font-size: 16px;vertical-align: middle;"></i></button>
                        
                        <div class="btn-fillter-sort float-right mx-5 toggle-sort sort-desc" role="button" data-sort="default:desc" style="display: block;">
                            <i class="ph-light ph-sort-ascending" style="font-size:16px;vertical-align:middle;"> </i> ใหม่ที่สุดก่อน
                        </div>

                        <div class="btn-fillter-sort float-right mx-5 toggle-sort sort-asc" role="button" data-sort="default:asc" style="display: none;">
                            <i class="ph-light ph-sort-descending" style="font-size:16px;vertical-align:middle;"> </i> เก่าที่สุดก่อน
                        </div>

                        <div class="btn-fillter-reset float-right mx-5" role="button" title="Reset" onclick="$.ResetFillter()">
                            <i class="ph-light ph-arrows-counter-clockwise" style="font-size: 16px;vertical-align: middle;"></i>
                        </div>

                    </div>
                </div>

                <div class="row tab-timeline">
                    <div class="bg-gradient_solid">
                      <div class=""><!-- container -->
                        
                        <div class="steps timelineList" id="timelineList"></div>

                      </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div id="myModal-product-plan" class="modal fade modal-product" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="display: inline;">Product Plan</h4>
                <button type="button" class="btn btn-default product-closed" data-dismiss="modal" style="float: right;">X</button>
            </div>
            <div class="modal-body">
                
                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order No.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-projectno" id="d-projectno" readonly>
                    </div>

                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order Name</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-projectname" id="d-projectname" readonly>
                    </div>

                </div>

                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product No.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-productno" id="d-productno" readonly>
                    </div>

                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product Order Name</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-productname" id="d-productname" readonly>
                    </div>

                </div>

                <div class="card-box mb-10">
                    <form id="form-productplan" method="post" action="" autocomplete="off">
                        <input type="hidden" name="plan-productid" id="plan-productid">
                        <input type="hidden" name="plan-projectid" id="plan-projectid">
                        <input type="hidden" name="plan-lineitem_id" id="plan-lineitem_id">
                        <div class="row">
                            <div class="col-2">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Plan Date <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-4">
                                <div class="base-input-group">
                                    <input type="hidden" id="Planlineitem_id" name="Planlineitem_id">
                                    <input type="text" class="base-input-text datepicker_input datepicker-input" id="product_plan_date" name="product_plan_date" required="" placeholder="DD/MM/YYYY">
                                    <div class="base-input-group-action">
                                        <i class="ph-calendar-blank cursor-pointer" for="product_plan_date"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-2">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Plan Qty <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="base-input" name="product_qty" id="product_qty" onkeypress=" return isNumberPricelist(event);" required="">
                            </div>

                            <div class="col-12 mt-10">
                                <button type="submit" class="btn btn-save-productplan float-right" >Save</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-box mb-5">
                    <div class="row">
                        <div class="col-5">Plan Date</div>
                        <div class="col-5">Plan Qty</div>
                        <div class="col-2"></div>
                    </div>
                </div>

                <div class="list-productplan mb-5" style="height: 200px; overflow:hidden; overflow-y: auto;">
                </div>

                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Plan Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-toatl-qty" id="d-toatl-qty" readonly>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4"></div>
                </div>

                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Estimate Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-estimate-qty" id="d-estimate-qty" readonly>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-dialog" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">กรอกเหตุผลการยกเลิก</h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div id="myModal-product-delivered" class="modal fade modal-product" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="display: inline;">Product Delivered</h4>
                <button type="button" class="btn btn-default product-closed" data-dismiss="modal" style="float: right;">X</button>
            </div>
            <div class="modal-body">
                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order No.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-delivered-projectno" id="d-delivered-projectno" readonly>
                    </div>

                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order Name</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-delivered-projectname" id="d-delivered-projectname" readonly>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product No.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-delivered-productno" id="d-delivered-productno" readonly>
                    </div>

                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product Order Name</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="d-delivered-productname" id="d-delivered-productname" readonly>
                    </div>
                </div>

                <div class="card-box mb-10">
                    <form id="form-product-delivered" method="post" action="" autocomplete="off" >
                        <input type="hidden" name="delivered-productid" id="delivered-productid">
                        <input type="hidden" name="delivered-projectid" id="delivered-projectid">
                        <input type="hidden" name="delivered-lineitem_id" id="delivered-lineitem_id">
                        
                        <div class="row mb-5">
                            <div class="col-2">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Dealer Delivered <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-10">
                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'dealerid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~M','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'dealerid1','fieldName'=> 'input-dealerid1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'productinventory'] ); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Delivered Date <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-4">
                                <div class="base-input-group">
                                    <input type="hidden" id="deliveredlineitem_id" name="deliveredlineitem_id">
                                    <input type="text" class="base-input-text datepicker_input datepicker-input" id="product_delivered_date" name="product_delivered_date" required="" placeholder="DD/MM/YYYY" onkeydown="if(event.keyCode === 13) { return false;}">
                                    <div class="base-input-group-action">
                                        <i class="ph-calendar-blank cursor-pointer" for="product_delivered_date"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Delivered Qty <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="base-input" name="product_delivered_qty" id="product_delivered_qty" required="" onkeypress=" return isNumberPricelist(event);" onkeydown="if(event.keyCode === 13) { return false;}">
                            </div>

                            <div class="col-12 mt-10">
                                <button type="submit" class="btn btn-save-delivered float-right">Save</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-box mb-5">
                    <div class="row">
                        <div class="col-4">Dealer Delivered</div>
                        <div class="col-4">Delivered Date</div>
                        <div class="col-2">Delivered Qty</div>
                        <div class="col-2"></div>
                    </div>
                </div>

                <div class="list-productdelivered mb-5" style="height: 200px; overflow:hidden; overflow-y: auto;">
                </div>

                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Delivery Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="toatl-delivery-qty" id="toatl-delivery-qty" readonly>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4"></div>
                </div>

                <div class="row mb-5">
                    <div class="col-2">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Plan Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="base-input base-input-text" name="toatl-plan-qty" id="toatl-plan-qty" readonly>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .modal-body{
        background-color: #fff;
    }
    .modal-body .base-input{
        width: 90%;
    }
    .modal-body .base-input-group{
        width: 90%;
    }
    .datepicker-dropdown.active{
        z-index: 1060 !important;
    }
    #popup_users {
        display: none;
        width: 150px;
        height: auto;
        margin-right: 0px;
        position: absolute;
        padding: 0;
        text-align: left;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        background-color: #ffffff;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
        padding: 10px;
        right: 20px;
        border-radius: 5px;
        z-index: 1000;
        top: 120px;
    }
    #popup_field {
        display: none;
        width: 200px;
        height: auto;
        margin-right: 0px;
        position: absolute;
        padding: 0;
        text-align: left;
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.2);
        background-color: #ffffff;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
        padding: 10px;
        right: 150px;
        border-radius: 5px;
        z-index: 1000;
        top: 120px;
    }
    .bg-popup{
        height: 100%;
        width: 100%;
        position: absolute;
        background-color: #dedede;
        opacity: 1;
        margin: 0;
        opacity: 0;
        z-index: 999;
        display: none;
    }
    .scrollbar{
        overflow-x: hidden;
        overflow-y: auto;
    }
    .container2 {
        display: block;
        position: relative;
        padding-left: 25px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 14px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: #2b2b2b;
        font-weight: 100;
    }
    .container2 input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    .checkmark {
        position: absolute;
        top: 2px;
        left: 0;
        height: 15px;
        width: 15px;
        background-color: #eee;
    }
    .container2 input:checked ~ .checkmark {
        background-color: #f0f9ff;
        border: 1px solid #018ffb;
    }
    .container2 .checkmark:after {
        left: 3px;
        top: -1px;
        width: 4px;
        height: 9px;
        border: solid #018ffb; 
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
        box-sizing: unset;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container2 input:checked ~ .checkmark:after {
        display: block;
    }
</style>
<style type="text/css">
    .steps {
      position: relative;
      margin-top: 32px;
    }
    .steps::after {
      content: "";
      position: absolute;
      width: 1px;
      background-color: #000;
      opacity: 0.4;
      top: 0;
      bottom: 0;
      left: 30px;
    }

    .steps .content p {
      color: #676767;
      font-size: 14px;
    }

    .steps .content h2 {
      font-weight: 600;
      font-size: 16px;
      color: #676767;
    }

    .steps-container {
      position: relative;
      background-color: inherit;
      /*width: calc(50% + 32px);*/
    }

    .steps-container .content {
      padding: 20px;
      background-color: white;
      position: relative;
      border-radius: 10px 10px 10px 10px;
      
      border: 1px solid #E2E8F0;
      width: -webkit-fill-available;
      z-index: 888;
    }
    .steps-container .content:hover{
        transform: scale(1.01);
        --tw-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --tw-shadow-colored: 0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
    }
    .steps .steps-container:nth-child(even) {
      /*left: calc(50% - 32px);*/
      /*flex-direction: row-reverse;*/
    }
    .steps .steps-container {
      /*left: calc(50% - 32px);*/
      flex-direction: row-reverse;
    }

    .steps-container {
      display: flex;
      margin-bottom: 20px;
    }

    .steps .steps-container .date {
      font-weight: 900;
      font-size: 16px;
      color: #ffffff;
      margin-bottom: 10px;
      width: 62px;
      height: 62px;
      background-color: #fff;
      border-radius: 50%;
      flex-shrink: 0;
      align-items: center;
      display: flex;
      justify-content: center;
      z-index: 777;
    }
    .date>img {
        width: 62px;
        height: 62px;
        border-radius: 50%;
        z-index: 777;
    }
    .step-line {
      width: 40px;
      background-color: #000;
      height: 1px;
      margin-top: 31px;
      opacity: 0.4;
      flex-shrink: 0;
    }
    .content > p{
        margin-bottom: 0;
    }
    .time{
        font-size: 14px;
        color: #94A3B8;
    }
    .txt-update{
        
        font-weight: 400;
    }
    .field-update{
        font-size: 14px !important;
        color: #000 !important;
        font-weight: 600;
    }
    .middle-bar {
        text-align: center;
        padding: 1em;
        background-color: #fff;
      }
      .date-timeline{
        color: #94A3B8;
        font-size: 14px;
      }
</style>
<style type="text/css">
    body{
        overflow: hidden !important;
    }
    .row>.col-4{
        padding-right: unset !important;
    }
    .nav-link.active {
        color: #495057 !important;
        background-color: #fff !important;
        border-bottom: 1px solid #000 !important;
    }
    .nav-item{
        text-align: center;
        width: 100px;
    }
    .nav-link:hover{
        color: #000;
    }

    ::-webkit-scrollbar {
      width: 14px;
      height: 14px;
    }

    ::-webkit-scrollbar-thumb {
      border: 4px solid rgba(0, 0, 0, 0);
      background-clip: padding-box;
      border-radius: 9999px;
      background-color: #AAAAAA;

      transition: all 0.4s;
      -moz-transition: all 0.4s;
      -webkit-transition: all 0.4s;
    }
    .tab-pane{
        overflow-x: hidden;
        overflow-y: auto;
    }
    .tab-suummary{
        overflow-x: hidden;
        overflow-y: auto;
    }
    .tab-timeline{
        overflow-x: hidden;
        overflow-y: auto;
    }
</style>
<style type="text/css">
    .disable_click{
        opacity: 0.5 !important;
    }
    .bg-custom{
        background-color: #F8FAFC;
        padding: 5px;
        border-radius: 15px;  
    }
    .btn-save-form{
        margin-left: 10px;
        width: 100px;
        margin-bottom: 10px;
        border-radius: 10px;
        vertical-align:sub;
    }
    .table-striped>tbody>tr:nth-of-type(odd)>*{
        --bs-table-accent-bg:unset !important;
    }
    .row_data{
        background-color: #F8FAFC !important;
    }
    .disable_button{
        cursor: no-drop;
        color:  #d5d5d5;
    }
    .disable_button_down{
        cursor: no-drop;
        color:  #d5d5d5;
    }
    .border-top-radius-5{
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }
    .border-bottom-radius-5{
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .border-top-radius-10{
        border-top-left-radius: 10px;
    }
    .border-bottom-radius-10{
        border-top-right-radius: 10px;
    }

    .table-bordered>:not(caption)>*{
        border-width: 0 !important;
    }
    .table-bordered>:not(caption)>*>*{
        border-width: 0 !important;
    }
    .table-product{
        margin-bottom: 10px;
    }
    .table-product::-webkit-scrollbar-track {
        background-color:#fff
    }
    .table-product::-webkit-scrollbar-track:hover {
        background-color:#f4f4f4
    }

    /* scrollbar itself */
    .table-product::-webkit-scrollbar-thumb {
        background-color:#babac0;
        border-radius:16px;
        border:5px solid #fff
    }
    .table-product::-webkit-scrollbar-thumb:hover {
        background-color:#a0a0a5;
        border:4px solid #f4f4f4
    }

    /* set button(top and bottom of the scrollbar) */
    .table-product::-webkit-scrollbar-button {display:none}
    
    .w-50px{
        width: 50px !important;
    }
    .w-100px{
        width: 100px !important;
    }
    .w-150px{
        width: 150px !important;
    }
    .w-200px{
        width: 200px !important;
    }
    .w-300px{
        width: 300px !important;
    }
    .w-400px{
        width: 400px !important;
    }

    .list-item-bar+.list-item-bar{
        border-top-width : 0;
    }
    .list-item-bar.active {
        z-index: 2;
        color: #000;
        border-left: 2px solid #000 !important;
    }
    .list-item-bar {
        position: relative;
        display: block;
        padding: 0.5rem 1rem;
        color: #a1a1a1;
        text-decoration: none;
        background-color: #fff;
        border-left: 1px solid rgba(0,0,0,.125) !important;
    }
    a.list-item-bar:hover{
        color: #000;
    }
    body{
        overflow-x: hidden;
    }
    ul.nav-pills {
        position: fixed;
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link{
        border-bottom: unset !important;
    }
    ::-webkit-scrollbar {
      width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      border-radius: 10px;
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #d9d9d9cf; 
      border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #838383; 
    }
    .lvtColData {
        background-color: #ffffff;
        border: 1px solid #F1F5F9;
        border-width : 1px !important;
        line-height: 10px;
    }
</style>

<script>
    var offSet = 0;
    var crmID = '<?php echo $crmID; ?>'
    var userID = '<?php echo $userID; ?>'
    var moduleSelect = 'Projects'

    function PopupUsers() {
        var popup_filter = $(`#popup_users`);
        if ($('#popup_users').css('display') === "block") {
            $('#popup_users').css('display', "none")
            $('#popup_users').css('width',"150px")
        } else {
            $('#popup_users').css('display', "block")
            $('.bg-popup').css('display','block');
        }
    }

    function PopupField() {
        
        var popup_filter = $(`#popup_field`);
        if ($('#popup_field').css('display') === "block") {
            $('#popup_field').css('display', "none")
            $('#popup_field').css('width',"200px")
        } else {
            $('#popup_field').css('display', "block")
            $('.bg-popup').css('display','block');
        }
    }
    function myKeyPress(modalID, obj, count, settype, e) {
        if(e.which == 13){//Enter key pressed
            var moduleSelect = $(obj).data('moduleselect')
            var fieldID = $(obj).data('field')
            var selectfield = $(`#${fieldID}-modal-select-box`).val()
            var search = $(`#${fieldID}-modal-search-box`).val()
            
            $(`#${fieldID}-modal-select-hidden`).val(selectfield)
            $(`#${fieldID}-modal-search-hidden`).val(search)
           
            $(`#${modalID}`).modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
            getPopupListMulti(moduleSelect,fieldID,count,settype,search,selectfield)
        }
    }
    function getPopupListMulti(moduleSelect, fieldID, Count, Settype, filter, selectfield, Page){
        
        var params = {moduleSelect, offSet}
        if(filter !== undefined) params.filter = filter
        if(selectfield !== undefined) params.selectfield = selectfield
        if(Page !== undefined) params.offSet = eval((Page*20)-20)
        
        if(filter === undefined) {
            $(`#${fieldID}-modal-search-box`).val('')
            $(`#${fieldID}-modal-search-hidden`).val('')
            $(`#${fieldID}-modal-select-hidden`).val('')
        }

        $.post('<?php echo site_url('Projects/getPopupList'); ?>', params, function(rs){
            $(`#list-${moduleSelect}-${fieldID}`).html('')
            var configmodule = <?php echo json_encode($configmodule); ?>;
            var total = rs['total'];
            var offset = rs['offset'];

            $(`#record-total-${fieldID}`).html(total)

            $(`#record-start-${fieldID}`).html('0')
            $(`#record-end-${fieldID}`).html('0')
            $(`#page-num-${fieldID}`).val('1')
            $(`#page-of-${fieldID}`).html('1')

            /*Hearder Table*/
            $(`#header-${fieldID}`).html('')
            var headItem = '';
            headItem += `<div class="col-3 p-10 bd-left bd-top bd-bottom bd-radius-l"><label class="font-14 font-bold">${configmodule[moduleSelect]['header'][0]}</label></div>`
            headItem += `<div class="col-3 p-10 bd-top bd-bottom"><label class="font-14 font-bold">${configmodule[moduleSelect]['header'][1]}</label></div>`
            headItem += `<div class="col-3 p-10 bd-top bd-bottom"><label class="font-14 font-bold">${configmodule[moduleSelect]['header'][2]}</label></div>`
            headItem += `<div class="col-3 p-10 bd-right bd-top bd-bottom bd-radius-r"><label class="font-14 font-bold">${configmodule[moduleSelect]['header'][3]}</label></div>`
            $(`#header-${fieldID}`).append(headItem)
            
            rs['row'].map(item => {
                var rowItem = $('<div />',{ class:` flex width-full bg-F8 px-15 py-5 mb-5 border-r10 row-h` })
                var rowHtml = `<div class="col-3 p-5"><label class="font-14" title="${item.no}">${cha_length(item.no,20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.name}">${cha_length(item.name,20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.record1}">${cha_length(item.record1,20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.record2}">${cha_length(item.record2,20)}</label></div>`

                $(rowItem).html(rowHtml)
                $(rowItem).click(function(){
                    $.setPopupValue_WebMulti(fieldID, item, Count, Settype)
                })
                
                $(`#list-${moduleSelect}-${fieldID}`).append(rowItem)
            })
            
            if(total != 0){
                var paging = Math.ceil(total/20);
                
                if(offset == 0){
                    if(total < 20 ){
                        $(`#record-start-${fieldID}`).html('1')
                        $(`#record-end-${fieldID}`).html(total)

                        $(`#start-${fieldID}`).addClass("disable_click");
                        $(`#previous-${fieldID}`).addClass("disable_click");
                        $(`#next-${fieldID}`).addClass("disable_click");
                        $(`#end-${fieldID}`).addClass("disable_click");
                    }else{
                        $(`#record-start-${fieldID}`).html('1')
                        $(`#record-end-${fieldID}`).html('20')

                        $(`#start-${fieldID}`).addClass("disable_click");
                        $(`#previous-${fieldID}`).addClass("disable_click");

                        $(`#start-${fieldID}`).attr('data-page', '1');

                        $(`#next-${fieldID}`).attr('data-page', '2');
                        $(`#end-${fieldID}`).attr('data-page', paging);

                        $(`#next-${fieldID}`).removeClass("disable_click");
                        $(`#end-${fieldID}`).removeClass("disable_click");
                    }
                }else{

                    var start = eval(((Page * 20) - 20) + 1);
                    var end = eval(start + (20 - 1));
                    if (end > total) {
                        end = total;
                    }
                    $(`#record-start-${fieldID}`).html(start)
                    $(`#record-end-${fieldID}`).html(end)

                    $(`#page-num-${fieldID}`).val(Page)
                                        
                 
                    if(Page < paging){

                        $(`#previous-${fieldID}`).attr('data-page', eval(Page-1));
                        $(`#next-${fieldID}`).attr('data-page', eval(Page+1));

                        $(`#start-${fieldID}`).removeClass("disable_click");
                        $(`#previous-${fieldID}`).removeClass("disable_click");
                        $(`#next-${fieldID}`).removeClass("disable_click");
                        $(`#end-${fieldID}`).removeClass("disable_click");

                    }else if(Page = paging){

                        $(`#start-${fieldID}`).removeClass("disable_click");
                        $(`#previous-${fieldID}`).removeClass("disable_click");

                        $(`#next-${fieldID}`).addClass("disable_click");
                        $(`#end-${fieldID}`).addClass("disable_click");

                        $(`#previous-${fieldID}`).attr('data-page', eval(Page-1));
                    }
                    //paging
                }
                $(`#page-of-${fieldID}`).html(paging)
            }
        },'json')
    }

    function calculate_plan_total(qty,lineitem){
        $('.plan-'+lineitem).val(eval(qty))
        var delivered = $('.delivered-'+lineitem).val()

        var remain_on_hand = (eval(qty)-eval(delivered));
        $('.remain_on_hand-'+lineitem).val(remain_on_hand)

        var total_plan = 0;
        $('.plan').each(function(){
            total_plan += eval($(this).val());
        });
        $('#total_plan').val(eval(total_plan))

        var total_remain = 0;
        $('.remain_on_hand').each(function(){
            total_remain += eval($(this).val());
        });
        $('#total_on_hand').val(eval(total_remain))
    }

    function calculate_delivery_total(qty,lineitem,delivered_date,clear){
        /*if(clear == true){*/
        if(delivered_date.first_delivered_date == '0000-00-00'){
            $('.first_delivered-'+lineitem).val('')
            $('.last_delivered-'+lineitem).val('')
        }else{
            /*first_delivered*/
            var f_objectDate = new Date(delivered_date.first_delivered_date);
            var f_day = f_objectDate.getDate();
            var f_month = f_objectDate.getMonth();
            var f_year = f_objectDate.getFullYear();
            f_value = f_day+"/"+(f_month + 1)+"/"+f_year;
            $('.first_delivered-'+lineitem).val(f_value)
            /*first_delivered*/

            /*last_delivered*/
            var l_objectDate = new Date(delivered_date.last_delivered_date);
            var l_day = l_objectDate.getDate();
            var l_month = l_objectDate.getMonth();
            var l_year = l_objectDate.getFullYear();
            l_value = l_day+"/"+(l_month + 1)+"/"+l_year;
            $('.last_delivered-'+lineitem).val(l_value)
            /*last_delivered*/
        }

        $('.delivered-'+lineitem).val(eval(qty))
        var plan = $('.plan-'+lineitem).val()

        var remain_on_hand = (eval(plan)-eval(qty));
        $('.remain_on_hand-'+lineitem).val(remain_on_hand)

        var total_deli = 0;
        $('.delivered').each(function(){
            total_deli += eval($(this).val());
        });
        $('#total_deli').val(eval(total_deli))

        var total_remain = 0;
        $('.remain_on_hand').each(function(){
            total_remain += eval($(this).val());
        });
        $('#total_on_hand').val(eval(total_remain))
    }
    
    function getRelated(moduleSelect,crmID){
        Related_visit(moduleSelect,crmID,'summary')
        Related_quotation(moduleSelect,crmID,'summary')
        Related_documents(moduleSelect,crmID,'summary')
        Related_pricelist(moduleSelect,crmID,'summary')
        Related_samplerequisition(moduleSelect,crmID,'summary')
        Related_expenses(moduleSelect,crmID,'summary')
        Related_questionnaire(moduleSelect,crmID,'summary')
    }

    function Related_visit(moduleSelect,crmID,type,Page){

        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedvisit'); ?>', params, function(rs){
            $('.overlay').show();
            
            $(`#summary-visit`).html('')

            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowVisit = `<div class="list-record-related" style="overflow-x: auto;">
                                    <div class="more-visit mb-10" style="width: 1000px;">
                                        <table id="get_activities" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-12 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขการเข้าพบ</td>
                                                <td class="pd-10 ">สถานะ</td>
                                                <td class="pd-10 ">หัวข้อเรื่อง</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowVisit += `<tr class="lvtColData font-12" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?action=DetailView&module=Calendar&record=${item.activityid}&activity_mode=Events&parenttab=Marketing" target="_blank">${item.activity_no}</a>
                                    </td>
                                    <td class="pd-10">${item.eventstatus}</td>
                                    <td class="pd-10">${item.activitytype}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.parentid}&parenttab=Marketing" target="_blank" title="${item.customer_name}">${cha_length(item.customer_name,20)}</a>
                                    </td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowVisit += `</table>
                                </div>`

                rowVisit +=`<div class="row footer-module-related font-12">
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-visit">0</span> - <span id="record-end-visit">0</span> of <span id="record-total-visit">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-8 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-visit" data-page="1" data-moduleselect="Calendar" onclick="$.getNavigationVisit('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-visit" data-page="1" data-moduleselect="Calendar" onclick="$.getNavigationVisit('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-visit" data-moduleselect="${moduleSelect}" data-visitid="${crmID}" data-type="${type}" id="page-num-visit" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-visit">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-visit" data-page="0" data-moduleselect="Calendar" onclick="$.getNavigationVisit('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-visit" data-page="0" data-moduleselect="Calendar" onclick="$.getNavigationVisit('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowVisit += `</div>`

                $(`#summary-visit`).append(rowVisit)
                
                $(`#record-start-visit`).html('0')
                $(`#record-end-visit`).html('0')
                $(`#page-num-visit`).val('1')
                $(`#page-of-visit`).html('1')

                $(`#record-total-visit`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-visit`).html('1')
                            $(`#record-end-visit`).html(total)

                            $(`#start-visit`).addClass("disable_click");
                            $(`#previous-visit`).addClass("disable_click");
                            $(`#next-visit`).addClass("disable_click");
                            $(`#end-visit`).addClass("disable_click");
                        }else{
                            $(`#record-start-visit`).html('1')
                            $(`#record-end-visit`).html('20')

                            $(`#start-visit`).addClass("disable_click");
                            $(`#previous-visit`).addClass("disable_click");

                            $(`#start-visit`).attr('data-page', '1');

                            $(`#next-visit`).attr('data-page', '2');
                            $(`#end-visit`).attr('data-page', paging);

                            $(`#next-visit`).removeClass("disable_click");
                            $(`#end-visit`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-visit`).html(start)
                        $(`#record-end-visit`).html(end)

                        $(`#page-num-visit`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-visit`).attr('data-page', eval(Page-1));
                            $(`#next-visit`).attr('data-page', eval(Page+1));

                            $(`#start-visit`).removeClass("disable_click");
                            $(`#previous-visit`).removeClass("disable_click");
                            $(`#next-visit`).removeClass("disable_click");
                            $(`#end-visit`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-visit`).removeClass("disable_click");
                            $(`#previous-visit`).removeClass("disable_click");

                            $(`#next-visit`).addClass("disable_click");
                            $(`#end-visit`).addClass("disable_click");

                            $(`#previous-visit`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-visit`).html(paging)
                }

                $('.input-pagenumber-visit').change(function(i, e){
                    var crmID = $(this).data('visitid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-visit`).val()
                    var totalPage = $(`#page-of-visit`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_visit(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowVisit = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#summary-visit`).append(rowVisit)
                $('.overlay').hide();
            }
        },'json')    
    }

    function Related_quotation(moduleSelect,crmID,type,Page){
       
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedquotation'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#summary-quotation`).html('')
            var site_URL = '<?php echo $site_URL; ?>'
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowQuo = `<div class="list-record-related" style="overflow-x: auto;">
                                    <div class="more-quotes mb-10" style="width: 1000px;">
                                        <table id="get_quotes" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-12 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขใบเสนอราคา</td>
                                                <td class="pd-10 ">สถานะใบเสนอราคา</td>
                                                <td class="pd-10 ">รูปแบบใบเสนอราคา</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowQuo += `<tr class="lvtColData font-12" bgcolor="white">
                                    <td class="pd-10"><a href="${site_URL}index.php?action=DetailView&module=Quotes&record=${item.quoteid}&parenttab=Sales" target="_blank">${item.quote_no}</a></td>
                                    <td class="pd-10">${item.quotation_status}</td>
                                    <td class="pd-10">${item.quotation_type}</td>
                                    <td class="pd-10"><a href="${site_URL}index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Marketing" target="_blank" title="${item.accountname}">${cha_length(item.accountname,20)}</a>
                                    </td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowQuo += `</table>
                                </div>`

                rowQuo +=`<div class="row footer-module-related font-12">
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-quotes">0</span> - <span id="record-end-quotes">0</span> of <span id="record-total-quotes">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-8 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-quotes" data-page="1" data-moduleselect="Quotes" onclick="$.getNavigationQuotation('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-quotes" data-page="1" data-moduleselect="Quotes" onclick="$.getNavigationQuotation('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-quotes" data-moduleselect="${moduleSelect}" data-quotesid="${crmID}" data-type="${type}" id="page-num-quotes" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-quotes">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-quotes" data-page="0" data-moduleselect="Quotes" onclick="$.getNavigationQuotation('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-quotes" data-page="0" data-moduleselect="Quotes" onclick="$.getNavigationQuotation('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowQuo += `</div>`

                $(`#summary-quotation`).append(rowQuo)
                
                $(`#record-start-quotes`).html('0')
                $(`#record-end-quotes`).html('0')
                $(`#page-num-quotes`).val('1')
                $(`#page-of-quotes`).html('1')

                $(`#record-total-quotes`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-quotes`).html('1')
                            $(`#record-end-quotes`).html(total)

                            $(`#start-quotes`).addClass("disable_click");
                            $(`#previous-quotes`).addClass("disable_click");
                            $(`#next-quotes`).addClass("disable_click");
                            $(`#end-quotes`).addClass("disable_click");
                        }else{
                            $(`#record-start-quotes`).html('1')
                            $(`#record-end-quotes`).html('20')

                            $(`#start-quotes`).addClass("disable_click");
                            $(`#previous-quotes`).addClass("disable_click");

                            $(`#start-quotes`).attr('data-page', '1');

                            $(`#next-quotes`).attr('data-page', '2');
                            $(`#end-quotes`).attr('data-page', paging);

                            $(`#next-quotes`).removeClass("disable_click");
                            $(`#end-quotes`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-quotes`).html(start)
                        $(`#record-end-quotes`).html(end)

                        $(`#page-num-quotes`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-quotes`).attr('data-page', eval(Page-1));
                            $(`#next-quotes`).attr('data-page', eval(Page+1));

                            $(`#start-quotes`).removeClass("disable_click");
                            $(`#previous-quotes`).removeClass("disable_click");
                            $(`#next-quotes`).removeClass("disable_click");
                            $(`#end-quotes`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-quotes`).removeClass("disable_click");
                            $(`#previous-quotes`).removeClass("disable_click");

                            $(`#next-quotes`).addClass("disable_click");
                            $(`#end-quotes`).addClass("disable_click");

                            $(`#previous-quotes`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-quotes`).html(paging)
                }

                $('.input-pagenumber-quotes').change(function(i, e){
                    var crmID = $(this).data('quotesid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-quotes`).val()
                    var totalPage = $(`#page-of-quotes`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_quotation(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowQuo = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#summary-quotation`).append(rowQuo)
                $('.overlay').hide();
            }
        },'json')    
    }
    
    function Related_documents(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedDocuments'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#summary-documents`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowDoc = `<div class="list-record-related" style="overflow-x: auto;">
                                    <div class="more-documents mb-10" style="width: 1000px;">
                                        <table id="get_documents" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-12 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">Document No</td>
                                                <td class="pd-10 ">ชื่อโฟลเดอร์</td>
                                                <td class="pd-10 ">Title</td>
                                                <td class="pd-10 ">ชื่อไฟล์</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowDoc += `<tr class="lvtColData font-12" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Documents&action=DetailView&record=${item.notesid}&parenttab=Inventory" target="_blank">${item.note_no}</a></td>
                                    <td class="pd-10">${item.foldername}</td>
                                    <td class="pd-10">${item.title}</td>
                                    <td class="pd-10">${item.filename}</td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowDoc += `</table>
                                </div>`

                rowDoc +=`<div class="row footer-module-related font-12">
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-doc">0</span> - <span id="record-end-doc">0</span> of <span id="record-total-doc">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-8 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-doc" data-page="1" data-moduleselect="Documents" onclick="$.getNavigationDocuments('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-doc" data-page="1" data-moduleselect="Documents" onclick="$.getNavigationDocuments('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-doc" data-moduleselect="${moduleSelect}" data-documentid="${crmID}" data-type="${type}" id="page-num-doc" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-doc">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-doc" data-page="0" data-moduleselect="Documents" onclick="$.getNavigationDocuments('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-doc" data-page="0" data-moduleselect="Documents" onclick="$.getNavigationDocuments('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowDoc += `</div>`

                $(`#summary-documents`).append(rowDoc)
                
                $(`#record-start-doc`).html('0')
                $(`#record-end-doc`).html('0')
                $(`#page-num-doc`).val('1')
                $(`#page-of-doc`).html('1')

                $(`#record-total-doc`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-doc`).html('1')
                            $(`#record-end-doc`).html(total)

                            $(`#start-doc`).addClass("disable_click");
                            $(`#previous-doc`).addClass("disable_click");
                            $(`#next-doc`).addClass("disable_click");
                            $(`#end-doc`).addClass("disable_click");
                        }else{
                            $(`#record-start-doc`).html('1')
                            $(`#record-end-doc`).html('20')

                            $(`#start-doc`).addClass("disable_click");
                            $(`#previous-doc`).addClass("disable_click");

                            $(`#start-doc`).attr('data-page', '1');

                            $(`#next-doc`).attr('data-page', '2');
                            $(`#end-doc`).attr('data-page', paging);

                            $(`#next-doc`).removeClass("disable_click");
                            $(`#end-doc`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-doc`).html(start)
                        $(`#record-end-doc`).html(end)

                        $(`#page-num-doc`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-doc`).attr('data-page', eval(Page-1));
                            $(`#next-doc`).attr('data-page', eval(Page+1));

                            $(`#start-doc`).removeClass("disable_click");
                            $(`#previous-doc`).removeClass("disable_click");
                            $(`#next-doc`).removeClass("disable_click");
                            $(`#end-doc`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-doc`).removeClass("disable_click");
                            $(`#previous-doc`).removeClass("disable_click");

                            $(`#next-doc`).addClass("disable_click");
                            $(`#end-doc`).addClass("disable_click");

                            $(`#previous-doc`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-doc`).html(paging)
                }

                $('.input-pagenumber-doc').change(function(i, e){
                    var crmID = $(this).data('documentid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-doc`).val()
                    var totalPage = $(`#page-of-doc`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_documents(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowDoc = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#summary-documents`).append(rowDoc)
                $('.overlay').hide();
            }
        },'json')    
    }

    function Related_pricelist(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedPricelist'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#summary-pricelist`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowPricelist = `<div class="list-record-related" style="overflow-x: auto;">
                                    <div class="more-pricelist mb-10" style="width: 1000px;">
                                        <table id="get_pricelist" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-12 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขรายการราคา</td>
                                                <td class="pd-10 ">ชื่อรายการราคา</td>
                                                <td class="pd-10 ">ใบเสนอราคา</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowPricelist += `<tr class="lvtColData font-12" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=PriceList&action=DetailView&record=${item.pricelistid}&parenttab=Inventory" target="_blank">${item.pricelist_no}</a></td>
                                    <td class="pd-10">${item.pricelist_name}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Quotes&action=DetailView&record=${item.quoteid}&parenttab=Inventory" target="_blank">${item.quote_no}</a></td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Inventory" target="_blank" title="${item.accountname}">${item.accountname}</a></td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowPricelist += `</table>
                                </div>`

                rowPricelist +=`<div class="row footer-module-related font-12">
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-pricelist">0</span> - <span id="record-end-pricelist">0</span> of <span id="record-total-pricelist">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-8 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-pricelist" data-page="1" data-moduleselect="PriceList" onclick="$.getNavigationPricelist('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-pricelist" data-page="1" data-moduleselect="PriceList" onclick="$.getNavigationPricelist('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-pricelist" data-moduleselect="${moduleSelect}" data-pricelistid="${crmID}" data-type="${type}" id="page-num-pricelist" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-pricelist">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-pricelist" data-page="0" data-moduleselect="PriceList" onclick="$.getNavigationPricelist('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-pricelist" data-page="0" data-moduleselect="PriceList" onclick="$.getNavigationPricelist('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowPricelist += `</div>`

                $(`#summary-pricelist`).append(rowPricelist)
                
                $(`#record-start-pricelist`).html('0')
                $(`#record-end-pricelist`).html('0')
                $(`#page-num-pricelist`).val('1')
                $(`#page-of-pricelist`).html('1')

                $(`#record-total-pricelist`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-pricelist`).html('1')
                            $(`#record-end-pricelist`).html(total)

                            $(`#start-pricelist`).addClass("disable_click");
                            $(`#previous-pricelist`).addClass("disable_click");
                            $(`#next-pricelist`).addClass("disable_click");
                            $(`#end-pricelist`).addClass("disable_click");
                        }else{
                            $(`#record-start-pricelist`).html('1')
                            $(`#record-end-pricelist`).html('20')

                            $(`#start-pricelist`).addClass("disable_click");
                            $(`#previous-pricelist`).addClass("disable_click");

                            $(`#start-pricelist`).attr('data-page', '1');

                            $(`#next-pricelist`).attr('data-page', '2');
                            $(`#end-pricelist`).attr('data-page', paging);

                            $(`#next-pricelist`).removeClass("disable_click");
                            $(`#end-pricelist`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-pricelist`).html(start)
                        $(`#record-end-pricelist`).html(end)

                        $(`#page-num-pricelist`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-pricelist`).attr('data-page', eval(Page-1));
                            $(`#next-pricelist`).attr('data-page', eval(Page+1));

                            $(`#start-pricelist`).removeClass("disable_click");
                            $(`#previous-pricelist`).removeClass("disable_click");
                            $(`#next-pricelist`).removeClass("disable_click");
                            $(`#end-pricelist`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-pricelist`).removeClass("disable_click");
                            $(`#previous-pricelist`).removeClass("disable_click");

                            $(`#next-pricelist`).addClass("disable_click");
                            $(`#end-pricelist`).addClass("disable_click");

                            $(`#previous-pricelist`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-pricelist`).html(paging)
                }

                $('.input-pagenumber-pricelist').change(function(i, e){
                    var crmID = $(this).data('pricelistid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-pricelist`).val()
                    var totalPage = $(`#page-of-pricelist`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_pricelist(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowPricelist = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#summary-pricelist`).append(rowPricelist)
                $('.overlay').hide();
            }
        },'json')    
    }

    function Related_samplerequisition(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedSamplerequisition'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#summary-samplerequisition`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowSamplerequisition = `<div class="list-record-related" style="overflow-x: auto;">
                                    <div class="more-samplerequisition mb-10" style="width: 1000px;">
                                        <table id="get_samplerequisition" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-12 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขใบขอตัวอย่าง</td>
                                                <td class="pd-10 ">สถานะใบขอตัวอย่าง</td>
                                                <td class="pd-10 ">ประเภทการขอตัวอย่าง</td>
                                                <td class="pd-10 ">ชื่อบริษัท</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowSamplerequisition += `<tr class="lvtColData font-12" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Samplerequisition&action=DetailView&record=${item.samplerequisitionid}&parenttab=Sales" target="_blank">${item.samplerequisition_no}</a></td>
                                    <td class="pd-10">${item.samplerequisition_status}</td>
                                    <td class="pd-10">${item.sample_request_type}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Inventory" target="_blank" title="${item.accountname}">${item.accountname}</a></td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowSamplerequisition += `</table>
                                </div>`

                rowSamplerequisition +=`<div class="row footer-module-related font-12">
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-samplerequisition">0</span> - <span id="record-end-samplerequisition">0</span> of <span id="record-total-samplerequisition">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-8 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-samplerequisition" data-page="1" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-samplerequisition" data-page="1" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-samplerequisition" data-moduleselect="${moduleSelect}" data-samplerequisitionid="${crmID}" data-type="${type}" id="page-num-samplerequisition" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-samplerequisition">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-samplerequisition" data-page="0" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-samplerequisition" data-page="0" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowSamplerequisition += `</div>`

                $(`#summary-samplerequisition`).append(rowSamplerequisition)
                
                $(`#record-start-samplerequisition`).html('0')
                $(`#record-end-samplerequisition`).html('0')
                $(`#page-num-samplerequisition`).val('1')
                $(`#page-of-samplerequisition`).html('1')

                $(`#record-total-samplerequisition`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-samplerequisition`).html('1')
                            $(`#record-end-samplerequisition`).html(total)

                            $(`#start-samplerequisition`).addClass("disable_click");
                            $(`#previous-samplerequisition`).addClass("disable_click");
                            $(`#next-samplerequisition`).addClass("disable_click");
                            $(`#end-samplerequisition`).addClass("disable_click");
                        }else{
                            $(`#record-start-samplerequisition`).html('1')
                            $(`#record-end-samplerequisition`).html('20')

                            $(`#start-samplerequisition`).addClass("disable_click");
                            $(`#previous-samplerequisition`).addClass("disable_click");

                            $(`#start-samplerequisition`).attr('data-page', '1');

                            $(`#next-samplerequisition`).attr('data-page', '2');
                            $(`#end-samplerequisition`).attr('data-page', paging);

                            $(`#next-samplerequisition`).removeClass("disable_click");
                            $(`#end-samplerequisition`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-samplerequisition`).html(start)
                        $(`#record-end-samplerequisition`).html(end)

                        $(`#page-num-samplerequisition`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-samplerequisition`).attr('data-page', eval(Page-1));
                            $(`#next-samplerequisition`).attr('data-page', eval(Page+1));

                            $(`#start-samplerequisition`).removeClass("disable_click");
                            $(`#previous-samplerequisition`).removeClass("disable_click");
                            $(`#next-samplerequisition`).removeClass("disable_click");
                            $(`#end-samplerequisition`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-samplerequisition`).removeClass("disable_click");
                            $(`#previous-samplerequisition`).removeClass("disable_click");

                            $(`#next-samplerequisition`).addClass("disable_click");
                            $(`#end-samplerequisition`).addClass("disable_click");

                            $(`#previous-samplerequisition`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-samplerequisition`).html(paging)
                }

                $('.input-pagenumber-samplerequisition').change(function(i, e){
                    var crmID = $(this).data('samplerequisitionid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-samplerequisition`).val()
                    var totalPage = $(`#page-of-samplerequisition`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_samplerequisition(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowSamplerequisition = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#summary-samplerequisition`).append(rowSamplerequisition)
                $('.overlay').hide();
            }
        },'json')    
    }

    function Related_expenses(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedExpenses'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#summary-expenses`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowExpenses = `<div class="list-record-related" style="overflow-x: auto;">
                                    <div class="more-expenses mb-10" style="width: 1000px;">
                                        <table id="get_expenses" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-12 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">Expense No</td>
                                                <td class="pd-10 ">ทะเบียนรถ</td>
                                                <td class="pd-10 ">วันที่ค่าใช้จ่าย</td>
                                                <td class="pd-10 ">ประเภทค่าใช้จ่าย</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowExpenses += `<tr class="lvtColData font-12" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Expense&action=DetailView&record=${item.expenseid}&parenttab=Inventory" target="_blank">${item.expense_no}</a></td>
                                    <td class="pd-10">${item.car_regis_no}</td>
                                    <td class="pd-10">${ConvertDateformat(item.expenses_date)}</td>
                                    <td class="pd-10"${item.expenses_type}</td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowExpenses += `</table>
                                </div>`

                rowExpenses +=`<div class="row footer-module-related font-12">
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-expenses">0</span> - <span id="record-end-expenses">0</span> of <span id="record-total-expenses">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-8 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-expenses" data-page="1" data-moduleselect="Expense" onclick="$.getNavigationExpense('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-expenses" data-page="1" data-moduleselect="Expense" onclick="$.getNavigationExpense('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-expenses" data-moduleselect="${moduleSelect}" data-expensesid="${crmID}" data-type="${type}" id="page-num-expenses" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-expenses">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-expenses" data-page="0" data-moduleselect="Expense" onclick="$.getNavigationExpense('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-expenses" data-page="0" data-moduleselect="Expense" onclick="$.getNavigationExpense('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowExpenses += `</div>`

                $(`#summary-expenses`).append(rowExpenses)
                
                $(`#record-start-expenses`).html('0')
                $(`#record-end-expenses`).html('0')
                $(`#page-num-expenses`).val('1')
                $(`#page-of-expenses`).html('1')

                $(`#record-total-expenses`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-expenses`).html('1')
                            $(`#record-end-expenses`).html(total)

                            $(`#start-expenses`).addClass("disable_click");
                            $(`#previous-expenses`).addClass("disable_click");
                            $(`#next-expenses`).addClass("disable_click");
                            $(`#end-expenses`).addClass("disable_click");
                        }else{
                            $(`#record-start-expenses`).html('1')
                            $(`#record-end-expenses`).html('20')

                            $(`#start-expenses`).addClass("disable_click");
                            $(`#previous-expenses`).addClass("disable_click");

                            $(`#start-expenses`).attr('data-page', '1');

                            $(`#next-expenses`).attr('data-page', '2');
                            $(`#end-expenses`).attr('data-page', paging);

                            $(`#next-expenses`).removeClass("disable_click");
                            $(`#end-expenses`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-expenses`).html(start)
                        $(`#record-end-expenses`).html(end)

                        $(`#page-num-expenses`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-expenses`).attr('data-page', eval(Page-1));
                            $(`#next-expenses`).attr('data-page', eval(Page+1));

                            $(`#start-expenses`).removeClass("disable_click");
                            $(`#previous-expenses`).removeClass("disable_click");
                            $(`#next-expenses`).removeClass("disable_click");
                            $(`#end-expenses`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-expenses`).removeClass("disable_click");
                            $(`#previous-expenses`).removeClass("disable_click");

                            $(`#next-expenses`).addClass("disable_click");
                            $(`#end-expenses`).addClass("disable_click");

                            $(`#previous-expenses`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-expenses`).html(paging)
                }

                $('.input-pagenumber-expenses').change(function(i, e){
                    var crmID = $(this).data('expensesid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-expenses`).val()
                    var totalPage = $(`#page-of-expenses`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_expenses(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowExpenses = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#summary-expenses`).append(rowExpenses)
                $('.overlay').hide();
            }
        },'json')    
    }

    function Related_questionnaire(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedQuestionnaire'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#summary-questionnaire`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowQuestionnaire = `<div class="list-record-related" style="overflow-x: auto;">
                                    <div class="more-questionnaire mb-10" style="width: 1000px;">
                                        <table id="get_questionnaire" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-12 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">Questionnaire No</td>
                                                <td class="pd-10 ">Questionnaire Name</td>
                                                <td class="pd-10 ">สถานะ</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowQuestionnaire += `<tr class="lvtColData font-12" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Questionnaire&action=DetailView&record=${item.questionnaireid}&parenttab=Inventory" target="_blank">${item.questionnaire_no}</a></td>
                                    <td class="pd-10">${item.questionnaire_name}</td>
                                    <td class="pd-10">${item.questionnaire_status}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Inventory" target="_blank">${item.accountname}</a></td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowQuestionnaire += `</table>
                                </div>`

                rowQuestionnaire +=`<div class="row footer-module-related font-12">
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-questionnaire">0</span> - <span id="record-end-questionnaire">0</span> of <span id="record-total-questionnaire">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-8 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-questionnaire" data-page="1" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-questionnaire" data-page="1" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-questionnaire" data-moduleselect="${moduleSelect}" data-questionnaireid="${crmID}" data-type="${type}" id="page-num-questionnaire" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-questionnaire">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-questionnaire" data-page="0" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-questionnaire" data-page="0" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowQuestionnaire += `</div>`

                $(`#summary-questionnaire`).append(rowQuestionnaire)
                
                $(`#record-start-questionnaire`).html('0')
                $(`#record-end-questionnaire`).html('0')
                $(`#page-num-questionnaire`).val('1')
                $(`#page-of-questionnaire`).html('1')

                $(`#record-total-questionnaire`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-questionnaire`).html('1')
                            $(`#record-end-questionnaire`).html(total)

                            $(`#start-questionnaire`).addClass("disable_click");
                            $(`#previous-questionnaire`).addClass("disable_click");
                            $(`#next-questionnaire`).addClass("disable_click");
                            $(`#end-questionnaire`).addClass("disable_click");
                        }else{
                            $(`#record-start-questionnaire`).html('1')
                            $(`#record-end-questionnaire`).html('20')

                            $(`#start-questionnaire`).addClass("disable_click");
                            $(`#previous-questionnaire`).addClass("disable_click");

                            $(`#start-questionnaire`).attr('data-page', '1');

                            $(`#next-questionnaire`).attr('data-page', '2');
                            $(`#end-questionnaire`).attr('data-page', paging);

                            $(`#next-questionnaire`).removeClass("disable_click");
                            $(`#end-questionnaire`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-questionnaire`).html(start)
                        $(`#record-end-questionnaire`).html(end)

                        $(`#page-num-questionnaire`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-questionnaire`).attr('data-page', eval(Page-1));
                            $(`#next-questionnaire`).attr('data-page', eval(Page+1));

                            $(`#start-questionnaire`).removeClass("disable_click");
                            $(`#previous-questionnaire`).removeClass("disable_click");
                            $(`#next-questionnaire`).removeClass("disable_click");
                            $(`#end-questionnaire`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-questionnaire`).removeClass("disable_click");
                            $(`#previous-questionnaire`).removeClass("disable_click");

                            $(`#next-questionnaire`).addClass("disable_click");
                            $(`#end-questionnaire`).addClass("disable_click");

                            $(`#previous-questionnaire`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-questionnaire`).html(paging)
                }

                $('.input-pagenumber-questionnaire').change(function(i, e){
                    var crmID = $(this).data('questionnaireid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-questionnaire`).val()
                    var totalPage = $(`#page-of-questionnaire`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_questionnaire(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowQuestionnaire = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#summary-questionnaire`).append(rowQuestionnaire)
                $('.overlay').hide();
            }
        },'json')    
    }
    
    /*Related*/
    function Related_visit_more(moduleSelect,crmID,type,Page){

        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedvisit'); ?>', params, function(rs){
            $('.overlay').show();
            
            $(`#related-visit`).html('')

            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowVisit = `<div class="list-record-related">
                                    <div class="more-visit-related mb-10">
                                        <table id="get_activities_related" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขการเข้าพบ</td>
                                                <td class="pd-10 ">สถานะ</td>
                                                <td class="pd-10 ">หัวข้อเรื่อง</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowVisit += `<tr class="lvtColData font-14" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?action=DetailView&module=Calendar&record=${item.activityid}&activity_mode=Events&parenttab=Marketing" target="_blank">${item.activity_no}</a>
                                    </td>
                                    <td class="pd-10">${item.eventstatus}</td>
                                    <td class="pd-10">${item.activitytype}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.parentid}&parenttab=Marketing" target="_blank" title="${item.customer_name}">${cha_length(item.customer_name,20)}</a>
                                    </td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowVisit += `</table>
                                </div>`

                rowVisit +=`<div class="row footer-module-related font-14">

                                <div class="col-4"></div>
                                <div class="col-4 txt-center m-a">
                                    <label>
                                        <span id="record-start-visit-more">0</span> - <span id="record-end-visit-more">0</span> of <span id="record-total-visit-more">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-4 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-visit-more" data-page="1" data-moduleselect="Calendar" onclick="$.getNavigationVisit_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-visit-more" data-page="1" data-moduleselect="Calendar" onclick="$.getNavigationVisit_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-visit-more" data-moduleselect="${moduleSelect}" data-visitid="${crmID}" data-type="${type}" id="page-num-visit-more" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-visit-more">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-visit-more" data-page="0" data-moduleselect="Calendar" onclick="$.getNavigationVisit_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-visit-more" data-page="0" data-moduleselect="Calendar" onclick="$.getNavigationVisit_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowVisit += `</div>`

                $(`#related-visit`).append(rowVisit)
                
                $(`#record-start-visit-more`).html('0')
                $(`#record-end-visit-more`).html('0')
                $(`#page-num-visit-more`).val('1')
                $(`#page-of-visit-more`).html('1')

                $(`#record-total-visit-more`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-visit-more`).html('1')
                            $(`#record-end-visit-more`).html(total)

                            $(`#start-visit-more`).addClass("disable_click");
                            $(`#previous-visit-more`).addClass("disable_click");
                            $(`#next-visit-more`).addClass("disable_click");
                            $(`#end-visit-more`).addClass("disable_click");
                        }else{
                            $(`#record-start-visit-more`).html('1')
                            $(`#record-end-visit-more`).html('20')

                            $(`#start-visit-more`).addClass("disable_click");
                            $(`#previous-visit-more`).addClass("disable_click");

                            $(`#start-visit-more`).attr('data-page', '1');

                            $(`#next-visit-more`).attr('data-page', '2');
                            $(`#end-visit-more`).attr('data-page', paging);

                            $(`#next-visit-more`).removeClass("disable_click");
                            $(`#end-visit-more`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-visit-more`).html(start)
                        $(`#record-end-visit-more`).html(end)

                        $(`#page-num-visit-more`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-visit-more`).attr('data-page', eval(Page-1));
                            $(`#next-visit-more`).attr('data-page', eval(Page+1));

                            $(`#start-visit-more`).removeClass("disable_click");
                            $(`#previous-visit-more`).removeClass("disable_click");
                            $(`#next-visit-more`).removeClass("disable_click");
                            $(`#end-visit-more`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-visit-more`).removeClass("disable_click");
                            $(`#previous--more`).removeClass("disable_click");

                            $(`#next-visit-more`).addClass("disable_click");
                            $(`#end-visit-more`).addClass("disable_click");

                            $(`#previous-visit-more`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-visit-more`).html(paging)
                }

                $('.input-pagenumber-visit-more').change(function(i, e){
                    var crmID = $(this).data('visitid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-visit-more`).val()
                    var totalPage = $(`#page-of-visit-more`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_visit_more(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowVisit = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#related-visit`).append(rowVisit)
                $('.overlay').hide();
            }
        },'json')    
    }
    function Related_quotation_more(moduleSelect,crmID,type,Page){
       
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedquotation'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#related-quotation`).html('')
            var site_URL = '<?php echo $site_URL; ?>'
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowQuo = `<div class="list-record-related">
                                    <div class="more-quotes-related mb-10">
                                        <table id="get_quotes_related" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขใบเสนอราคา</td>
                                                <td class="pd-10 ">สถานะใบเสนอราคา</td>
                                                <td class="pd-10 ">รูปแบบใบเสนอราคา</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowQuo += `<tr class="lvtColData font-14" bgcolor="white">
                                    <td class="pd-10"><a href="${site_URL}index.php?action=DetailView&module=Quotes&record=${item.quoteid}&parenttab=Sales" target="_blank">${item.quote_no}</a></td>
                                    <td class="pd-10">${item.quotation_status}</td>
                                    <td class="pd-10">${item.quotation_type}</td>
                                    <td class="pd-10"><a href="${site_URL}index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Marketing" target="_blank" title="${item.accountname}">${cha_length(item.accountname,20)}</a>
                                    </td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowQuo += `</table>
                                </div>`

                rowQuo +=`<div class="row footer-module-related font-14">
                                <div class="col-4 txt-center m-a"></div>
                                <div class="col-4 txt-center m-a">
                                    <label>
                                        <span id="record-start-quotes-more">0</span> - <span id="record-end-quotes-more">0</span> of <span id="record-total-quotes-more">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-4 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-quotes-more" data-page="1" data-moduleselect="Quotes" onclick="$.getNavigationQuotation_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-quotes-more" data-page="1" data-moduleselect="Quotes" onclick="$.getNavigationQuotation_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-quotes-more" data-moduleselect="${moduleSelect}" data-quotesid="${crmID}" data-type="${type}" id="page-num-quotes-more" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-quotes-more">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-quotes-more" data-page="0" data-moduleselect="Quotes" onclick="$.getNavigationQuotation_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-quotes-more" data-page="0" data-moduleselect="Quotes" onclick="$.getNavigationQuotation_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowQuo += `</div>`

                $(`#related-quotation`).append(rowQuo)
                
                $(`#record-start-quotes-more`).html('0')
                $(`#record-end-quotes-more`).html('0')
                $(`#page-num-quotes-more`).val('1')
                $(`#page-of-quotes-more`).html('1')

                $(`#record-total-quotes-more`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-quotes-more`).html('1')
                            $(`#record-end-quotes-more`).html(total)

                            $(`#start-quotes-more`).addClass("disable_click");
                            $(`#previous-quotes-more`).addClass("disable_click");
                            $(`#next-quotes-more`).addClass("disable_click");
                            $(`#end-quotes-more`).addClass("disable_click");
                        }else{
                            $(`#record-start-quotes-more`).html('1')
                            $(`#record-end-quotes-more`).html('20')

                            $(`#start-quotes`).addClass("disable_click");
                            $(`#previous-quotes`).addClass("disable_click");

                            $(`#start-quotes`).attr('data-page', '1');

                            $(`#next-quotes-more`).attr('data-page', '2');
                            $(`#end-quotes-more`).attr('data-page', paging);

                            $(`#next-quotes-more`).removeClass("disable_click");
                            $(`#end-quotes-more`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-quotes-more`).html(start)
                        $(`#record-end-quotes-more`).html(end)

                        $(`#page-num-quotes-more`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-quotes-more`).attr('data-page', eval(Page-1));
                            $(`#next-quotes-more`).attr('data-page', eval(Page+1));

                            $(`#start-quotes-more`).removeClass("disable_click");
                            $(`#previous-quotes-more`).removeClass("disable_click");
                            $(`#next-quotes-more`).removeClass("disable_click");
                            $(`#end-quotes-more`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-quotes-more`).removeClass("disable_click");
                            $(`#previous-quotes-more`).removeClass("disable_click");

                            $(`#next-quotes-more`).addClass("disable_click");
                            $(`#end-quotes-more`).addClass("disable_click");

                            $(`#previous-quotes-more`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-quotes-more`).html(paging)
                }

                $('.input-pagenumber-quotes-more').change(function(i, e){
                    var crmID = $(this).data('quotesid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-quotes-more`).val()
                    var totalPage = $(`#page-of-quotes-more`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_quotation_more(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowQuo = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#related-quotation`).append(rowQuo)
                $('.overlay').hide();
            }
        },'json')    
    }
    function Related_documents_more(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedDocuments'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#related-documents`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowDoc = `<div class="list-record-related">
                                    <div class="more-documents-related mb-10">
                                        <table id="get_documents-related" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">Document No</td>
                                                <td class="pd-10 ">ชื่อโฟลเดอร์</td>
                                                <td class="pd-10 ">Title</td>
                                                <td class="pd-10 ">ชื่อไฟล์</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowDoc += `<tr class="lvtColData font-14" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Documents&action=DetailView&record=${item.notesid}&parenttab=Inventory" target="_blank">${item.note_no}</a></td>
                                    <td class="pd-10">${item.foldername}</td>
                                    <td class="pd-10">${item.title}</td>
                                    <td class="pd-10">${item.filename}</td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowDoc += `</table>
                                </div>`

                rowDoc +=`<div class="row footer-module-related font-14">
                                <div class="col-4 txt-right m-a"></div>
                                <div class="col-4 txt-center m-a">
                                    <label>
                                        <span id="record-start-doc-more">0</span> - <span id="record-end-doc-more">0</span> of <span id="record-total-doc-more">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-4 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-doc-more" data-page="1" data-moduleselect="Documents" onclick="$.getNavigationDocuments_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-doc-more" data-page="1" data-moduleselect="Documents" onclick="$.getNavigationDocuments_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-doc-more" data-moduleselect="${moduleSelect}" data-documentid="${crmID}" data-type="${type}" id="page-num-doc-more" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-doc-more">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-doc-more" data-page="0" data-moduleselect="Documents" onclick="$.getNavigationDocuments_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-doc-more" data-page="0" data-moduleselect="Documents" onclick="$.getNavigationDocuments_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowDoc += `</div>`

                $(`#related-documents`).append(rowDoc)
                
                $(`#record-start-doc-more`).html('0')
                $(`#record-end-doc-more`).html('0')
                $(`#page-num-doc-more`).val('1')
                $(`#page-of-doc-more`).html('1')

                $(`#record-total-doc-more`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-doc-more`).html('1')
                            $(`#record-end-doc-more`).html(total)

                            $(`#start-doc-more`).addClass("disable_click");
                            $(`#previous-doc-more`).addClass("disable_click");
                            $(`#next-doc-more`).addClass("disable_click");
                            $(`#end-doc-more`).addClass("disable_click");
                        }else{
                            $(`#record-start-doc-more`).html('1')
                            $(`#record-end-doc-more`).html('20')

                            $(`#start-doc-more`).addClass("disable_click");
                            $(`#previous-doc-more`).addClass("disable_click");

                            $(`#start-doc-more`).attr('data-page', '1');

                            $(`#next-doc-more`).attr('data-page', '2');
                            $(`#end-doc-more`).attr('data-page', paging);

                            $(`#next-doc-more`).removeClass("disable_click");
                            $(`#end-doc-more`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-doc-more`).html(start)
                        $(`#record-end-doc-more`).html(end)

                        $(`#page-num-doc-more`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-doc-more`).attr('data-page', eval(Page-1));
                            $(`#next-doc-more`).attr('data-page', eval(Page+1));

                            $(`#start-doc-more`).removeClass("disable_click");
                            $(`#previous-doc-more`).removeClass("disable_click");
                            $(`#next-doc-more`).removeClass("disable_click");
                            $(`#end-doc-more`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-doc-more`).removeClass("disable_click");
                            $(`#previous-doc-more`).removeClass("disable_click");

                            $(`#next-doc-more`).addClass("disable_click");
                            $(`#end-doc-more`).addClass("disable_click");

                            $(`#previous-doc-more`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-doc-more`).html(paging)
                }

                $('.input-pagenumber-doc-more').change(function(i, e){
                    var crmID = $(this).data('documentid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-doc-more`).val()
                    var totalPage = $(`#page-of-doc-more`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_documents_more(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowDoc = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#related-documents`).append(rowDoc)
                $('.overlay').hide();
            }
        },'json')    
    }
    function Related_pricelist_more(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedPricelist'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#related-pricelist`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowPricelist = `<div class="list-record-related">
                                    <div class="more-pricelist-related mb-10">
                                        <table id="get_pricelist_related" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขรายการราคา</td>
                                                <td class="pd-10 ">ชื่อรายการราคา</td>
                                                <td class="pd-10 ">ใบเสนอราคา</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowPricelist += `<tr class="lvtColData font-14" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=PriceList&action=DetailView&record=${item.pricelistid}&parenttab=Inventory" target="_blank">${item.pricelist_no}</a></td>
                                    <td class="pd-10">${item.pricelist_name}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Quotes&action=DetailView&record=${item.quoteid}&parenttab=Inventory" target="_blank">${item.quote_no}</a></td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Inventory" target="_blank" title="${item.accountname}">${item.accountname}</a></td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowPricelist += `</table>
                                </div>`

                rowPricelist +=`<div class="row footer-module-related font-14">
                                <div class="col-4 txt-left m-a"></div>
                                <div class="col-4 txt-center m-a">
                                    <label>
                                        <span id="record-start-pricelist-more">0</span> - <span id="record-end-pricelist-more">0</span> of <span id="record-total-pricelist-more">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-4 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page" role="button" id="start-pricelist-more" data-page="1" data-moduleselect="PriceList" onclick="$.getNavigationPricelist_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page" role="button" id="previous-pricelist-more" data-page="1" data-moduleselect="PriceList" onclick="$.getNavigationPricelist_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-pricelist-more" data-moduleselect="${moduleSelect}" data-pricelistid="${crmID}" data-type="${type}" id="page-num-pricelist-more" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-pricelist-more">0</span>
                                    
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page" role="button" id="next-pricelist-more" data-page="0" data-moduleselect="PriceList" onclick="$.getNavigationPricelist_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page" role="button" id="end-pricelist-more" data-page="0" data-moduleselect="PriceList" onclick="$.getNavigationPricelist_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>
                                </div>
                            </div>`

                rowPricelist += `</div>`

                $(`#related-pricelist`).append(rowPricelist)
                
                $(`#record-start-pricelist-more`).html('0')
                $(`#record-end-pricelist-more`).html('0')
                $(`#page-num-pricelist-more`).val('1')
                $(`#page-of-pricelist-more`).html('1')

                $(`#record-total-pricelist-more`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-pricelist-more`).html('1')
                            $(`#record-end-pricelist-more`).html(total)

                            $(`#start-pricelist-more`).addClass("disable_click");
                            $(`#previous-pricelist-more`).addClass("disable_click");
                            $(`#next-pricelist-more`).addClass("disable_click");
                            $(`#end-pricelist-more`).addClass("disable_click");
                        }else{
                            $(`#record-start-pricelist-more`).html('1')
                            $(`#record-end-pricelist-more`).html('20')

                            $(`#start-pricelist-more`).addClass("disable_click");
                            $(`#previous-pricelist-more`).addClass("disable_click");

                            $(`#start-pricelist-more`).attr('data-page', '1');

                            $(`#next-pricelist-more`).attr('data-page', '2');
                            $(`#end-pricelist-more`).attr('data-page', paging);

                            $(`#next-pricelist-more`).removeClass("disable_click");
                            $(`#end-pricelist-more`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-pricelist-more`).html(start)
                        $(`#record-end-pricelist-more`).html(end)

                        $(`#page-num-pricelist-more`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-pricelist-more`).attr('data-page', eval(Page-1));
                            $(`#next-pricelist-more`).attr('data-page', eval(Page+1));

                            $(`#start-pricelist-more`).removeClass("disable_click");
                            $(`#previous-pricelist-more`).removeClass("disable_click");
                            $(`#next-pricelist-more`).removeClass("disable_click");
                            $(`#end-pricelist-more`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-pricelist-more`).removeClass("disable_click");
                            $(`#previous-pricelist-more`).removeClass("disable_click");

                            $(`#next-pricelist-more`).addClass("disable_click");
                            $(`#end-pricelist-more`).addClass("disable_click");

                            $(`#previous-pricelist-more`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-pricelist-more`).html(paging)
                }

                $('.input-pagenumber-pricelist-more').change(function(i, e){
                    var crmID = $(this).data('pricelistid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-pricelist-more`).val()
                    var totalPage = $(`#page-of-pricelist-more`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_pricelist_more(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowPricelist = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#related-pricelist`).append(rowPricelist)
                $('.overlay').hide();
            }
        },'json')    
    }
    function Related_samplerequisition_more(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedSamplerequisition'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#related-samplerequisition`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowSamplerequisition = `<div class="list-record-related">
                                    <div class="more-samplerequisition-related mb-10">
                                        <table id="get_samplerequisition_related" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">หมายเลขใบขอตัวอย่าง</td>
                                                <td class="pd-10 ">สถานะใบขอตัวอย่าง</td>
                                                <td class="pd-10 ">ประเภทการขอตัวอย่าง</td>
                                                <td class="pd-10 ">ชื่อบริษัท</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowSamplerequisition += `<tr class="lvtColData font-14" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Samplerequisition&action=DetailView&record=${item.samplerequisitionid}&parenttab=Sales" target="_blank">${item.samplerequisition_no}</a></td>
                                    <td class="pd-10">${item.samplerequisition_status}</td>
                                    <td class="pd-10">${item.sample_request_type}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Inventory" target="_blank" title="${item.accountname}">${item.accountname}</a></td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowSamplerequisition += `</table>
                                </div>`

                rowSamplerequisition +=`<div class="row footer-module-related font-14">
                                <div class="col-4 txt-left m-a"></div>
                                <div class="col-4 txt-center m-a">
                                    <label>
                                        <span id="record-start-samplerequisition-more">0</span> - <span id="record-end-samplerequisition-more">0</span> of <span id="record-total-samplerequisition-more">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-4 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-samplerequisition-more" data-page="1" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-samplerequisition-more" data-page="1" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-samplerequisition-more" data-moduleselect="${moduleSelect}" data-samplerequisitionid="${crmID}" data-type="${type}" id="page-num-samplerequisition-more" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-samplerequisition-more">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-samplerequisition-more" data-page="0" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-samplerequisition-more" data-page="0" data-moduleselect="Samplerequisition" onclick="$.getNavigationSamplerequisition_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowSamplerequisition += `</div>`

                $(`#related-samplerequisition`).append(rowSamplerequisition)
                
                $(`#record-start-samplerequisition-more`).html('0')
                $(`#record-end-samplerequisition-more`).html('0')
                $(`#page-num-samplerequisition-more`).val('1')
                $(`#page-of-samplerequisition-more`).html('1')

                $(`#record-total-samplerequisition-more`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-samplerequisition-more`).html('1')
                            $(`#record-end-samplerequisition-more`).html(total)

                            $(`#start-samplerequisition-more`).addClass("disable_click");
                            $(`#previous-samplerequisition-more`).addClass("disable_click");
                            $(`#next-samplerequisition-more`).addClass("disable_click");
                            $(`#end-samplerequisition-more`).addClass("disable_click");
                        }else{
                            $(`#record-start-samplerequisition-more`).html('1')
                            $(`#record-end-samplerequisition-more`).html('20')

                            $(`#start-samplerequisition-more`).addClass("disable_click");
                            $(`#previous-samplerequisition-more`).addClass("disable_click");

                            $(`#start-samplerequisition-more`).attr('data-page', '1');

                            $(`#next-samplerequisition-more`).attr('data-page', '2');
                            $(`#end-samplerequisition-more`).attr('data-page', paging);

                            $(`#next-samplerequisition-more`).removeClass("disable_click");
                            $(`#end-samplerequisition-more`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-samplerequisition-more`).html(start)
                        $(`#record-end-samplerequisition-more`).html(end)

                        $(`#page-num-samplerequisition-more`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-samplerequisition-more`).attr('data-page', eval(Page-1));
                            $(`#next-samplerequisition-more`).attr('data-page', eval(Page+1));

                            $(`#start-samplerequisition-more`).removeClass("disable_click");
                            $(`#previous-samplerequisition-more`).removeClass("disable_click");
                            $(`#next-samplerequisition-more`).removeClass("disable_click");
                            $(`#end-samplerequisition-more`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-samplerequisition-more`).removeClass("disable_click");
                            $(`#previous-samplerequisition-more`).removeClass("disable_click");

                            $(`#next-samplerequisition-more`).addClass("disable_click");
                            $(`#end-samplerequisition-more`).addClass("disable_click");

                            $(`#previous-samplerequisition-more`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-samplerequisition-more`).html(paging)
                }

                $('.input-pagenumber-samplerequisition-more').change(function(i, e){
                    var crmID = $(this).data('samplerequisitionid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-samplerequisition-more`).val()
                    var totalPage = $(`#page-of-samplerequisition-more`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_samplerequisition_more(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowSamplerequisition = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#related-samplerequisition`).append(rowSamplerequisition)
                $('.overlay').hide();
            }
        },'json')    
    }
    function Related_expenses_more(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedExpenses'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#related-expenses`).html('')
            if(rs.Type === 'S'){
                var total = rs.total
                var offset = rs.offset
                var rowExpenses = `<div class="list-record-related">
                                    <div class="more-expenses-related mb-10">
                                        <table id="get_expenses_related" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">Expense No</td>
                                                <td class="pd-10 ">ทะเบียนรถ</td>
                                                <td class="pd-10 ">วันที่ค่าใช้จ่าย</td>
                                                <td class="pd-10 ">ประเภทค่าใช้จ่าย</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowExpenses += `<tr class="lvtColData font-14" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Expense&action=DetailView&record=${item.expenseid}&parenttab=Inventory" target="_blank">${item.expense_no}</a></td>
                                    <td class="pd-10">${item.car_regis_no}</td>
                                    <td class="pd-10">${ConvertDateformat(item.expenses_date)}</td>
                                    <td class="pd-10"${item.expenses_type}</td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowExpenses += `</table>
                                </div>`

                rowExpenses +=`<div class="row footer-module-related font-14">
                                <div class="col-4 txt-left m-a"></div>
                                <div class="col-4 txt-left m-a">
                                    <label>
                                        <span id="record-start-expenses-more">0</span> - <span id="record-end-expenses-more">0</span> of <span id="record-total-expenses-more">0</span>
                                    </label>
                                </div>
                                
                                <div class="col-4 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button" id="start-expenses-more" data-page="1" data-moduleselect="Expense" onclick="$.getNavigationExpense_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="previous-expenses-more" data-page="1" data-moduleselect="Expense" onclick="$.getNavigationExpense_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-expenses-more" data-moduleselect="${moduleSelect}" data-expensesid="${crmID}" data-type="${type}" id="page-num-expenses-more" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-expenses-more">0</span>
                                    
                                    <div class="btn-arrow" role="button" id="next-expenses-more" data-page="0" data-moduleselect="Expense" onclick="$.getNavigationExpense_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page"></i>
                                    </div>
                                    <div class="btn-arrow" role="button" id="end-expenses-more" data-page="0" data-moduleselect="Expense" onclick="$.getNavigationExpense_More('${moduleSelect}','${crmID}','${type}',this, event)">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page"></i>
                                    </div>
                                </div>
                            </div>`

                rowExpenses += `</div>`

                $(`#related-expenses`).append(rowExpenses)
                
                $(`#record-start-expenses-more`).html('0')
                $(`#record-end-expenses-more`).html('0')
                $(`#page-num-expenses-more`).val('1')
                $(`#page-of-expenses-more`).html('1')

                $(`#record-total-expenses-more`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-expenses-more`).html('1')
                            $(`#record-end-expenses-more`).html(total)

                            $(`#start-expenses-more`).addClass("disable_click");
                            $(`#previous-expenses-more`).addClass("disable_click");
                            $(`#next-expenses-more`).addClass("disable_click");
                            $(`#end-expenses-more`).addClass("disable_click");
                        }else{
                            $(`#record-start-expenses-more`).html('1')
                            $(`#record-end-expenses-more`).html('20')

                            $(`#start-expenses-more`).addClass("disable_click");
                            $(`#previous-expenses-more`).addClass("disable_click");

                            $(`#start-expenses-more`).attr('data-page', '1');

                            $(`#next-expenses-more`).attr('data-page', '2');
                            $(`#end-expenses-more`).attr('data-page', paging);

                            $(`#next-expenses-more`).removeClass("disable_click");
                            $(`#end-expenses-more`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-expenses-more`).html(start)
                        $(`#record-end-expenses-more`).html(end)

                        $(`#page-num-expenses-more`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-expenses-more`).attr('data-page', eval(Page-1));
                            $(`#next-expenses-more`).attr('data-page', eval(Page+1));

                            $(`#start-expenses-more`).removeClass("disable_click");
                            $(`#previous-expenses-more`).removeClass("disable_click");
                            $(`#next-expenses-more`).removeClass("disable_click");
                            $(`#end-expenses-more`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-expenses-more`).removeClass("disable_click");
                            $(`#previous-expenses-more`).removeClass("disable_click");

                            $(`#next-expenses-more`).addClass("disable_click");
                            $(`#end-expenses-more`).addClass("disable_click");

                            $(`#previous-expenses-more`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-expenses-more`).html(paging)
                }

                $('.input-pagenumber-expenses-more').change(function(i, e){
                    var crmID = $(this).data('expensesid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-expenses-more`).val()
                    var totalPage = $(`#page-of-expenses-more`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_expenses_more(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowExpenses = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#related-expenses`).append(rowExpenses)
                $('.overlay').hide();
            }
        },'json')    
    }
    function Related_questionnaire_more(moduleSelect,crmID,type,Page){
        
        params = {moduleSelect,crmID,offSet}
        if(Page !== undefined) params.offSet = eval((Page*20)-20)

        $.post('<?php echo site_url('Projects/getRelatedQuestionnaire'); ?>', params, function(rs){
            $('.overlay').show();
            $(`#related-questionnaire`).html('')
            if(rs.Type === 'S'){

                var total = rs.total
                var offset = rs.offset
                var rowQuestionnaire = `<div class="list-record-related">
                                    <div class="more-questionnaire-related mb-10">
                                        <table id="get_questionnaire_related" class="table table-striped table-sm" cellspacing="0" style="width: 100%;">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 border-top-radius-10">Questionnaire No</td>
                                                <td class="pd-10 ">Questionnaire Name</td>
                                                <td class="pd-10 ">สถานะ</td>
                                                <td class="pd-10 ">ชื่อลูกค้า</td>
                                                <td class="pd-10 border-bottom-radius-10">ผู้รับผิดชอบ</td>
                                            </tr>`

                rs['row'].map(item => {
                    rowQuestionnaire += `<tr class="lvtColData font-14" bgcolor="white">
                                    <td class="pd-10"><a href="../../../index.php?module=Questionnaire&action=DetailView&record=${item.questionnaireid}&parenttab=Inventory" target="_blank">${item.questionnaire_no}</a></td>
                                    <td class="pd-10">${item.questionnaire_name}</td>
                                    <td class="pd-10">${item.questionnaire_status}</td>
                                    <td class="pd-10"><a href="../../../index.php?module=Accounts&action=DetailView&record=${item.accountid}&parenttab=Inventory" target="_blank">${item.accountname}</a></td>
                                    <td class="pd-10">${item.user_name}</td>
                                </tr>`
                })

                rowQuestionnaire += `</table>
                                </div>`

                rowQuestionnaire +=`<div class="row footer-module-related font-14">
                                <div class="col-4 txt-left m-a"></div>
                                <div class="col-4 txt-center m-a">
                                    <label>
                                        <span id="record-start-questionnaire-more">0</span> - <span id="record-end-questionnaire-more">0</span> of <span id="record-total-questionnaire-more">0</span>
                                    </label>
                                </div>
                                <div class="col-4 txt-right" style="padding-right: calc(var(--bs-gutter-x) * .5) !important;">
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-double-left v-align-middle start-page" role="button" id="start-questionnaire-more" data-page="1" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-left v-align-middle previous-page" role="button" id="previous-questionnaire-more" data-page="1" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>

                                    <input type="text" class="base-input-text w-10 bg-white pagenumber ml-5 txt-center input-pagenumber-questionnaire-more" data-moduleselect="${moduleSelect}" data-questionnaireid="${crmID}" data-type="${type}" id="page-num-questionnaire-more" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-questionnaire-more">0</span>
                                    
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-right v-align-middle next-page" role="button" id="next-questionnaire-more" data-page="0" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>
                                    <div class="btn-arrow" role="button">
                                        <i class="ph-bold ph-caret-double-right v-align-middle end-page" role="button" id="end-questionnaire-more" data-page="0" data-moduleselect="Questionnaire" onclick="$.getNavigationQuestionnaire_More('${moduleSelect}','${crmID}','${type}',this, event)"></i>
                                    </div>
                                </div>
                            </div>`

                rowQuestionnaire += `</div>`

                $(`#related-questionnaire`).append(rowQuestionnaire)
                
                $(`#record-start-questionnaire-more`).html('0')
                $(`#record-end-questionnaire-more`).html('0')
                $(`#page-num-questionnaire-more`).val('1')
                $(`#page-of-questionnaire-more`).html('1')

                $(`#record-total-questionnaire-more`).html(total)

                if(total != 0){
                    var paging = Math.ceil(total/20);
                    
                    if(offset == 0){
                        if(total < 20 ){
                            $(`#record-start-questionnaire-more`).html('1')
                            $(`#record-end-questionnaire-more`).html(total)

                            $(`#start-questionnaire-more`).addClass("disable_click");
                            $(`#previous-questionnaire-more`).addClass("disable_click");
                            $(`#next-questionnaire-more`).addClass("disable_click");
                            $(`#end-questionnaire-more`).addClass("disable_click");
                        }else{
                            $(`#record-start-questionnaire-more`).html('1')
                            $(`#record-end-questionnaire-more`).html('20')

                            $(`#start-questionnaire-more`).addClass("disable_click");
                            $(`#previous-questionnaire-more`).addClass("disable_click");

                            $(`#start-questionnaire-more`).attr('data-page', '1');

                            $(`#next-questionnaire-more`).attr('data-page', '2');
                            $(`#end-questionnaire-more`).attr('data-page', paging);

                            $(`#next-questionnaire-more`).removeClass("disable_click");
                            $(`#end-questionnaire-more`).removeClass("disable_click");
                        }
                        
                    }else{

                        var start = eval(((Page * 20) - 20) + 1);
                        var end = eval(start + (20 - 1));
                        if (end > total) {
                            end = total;
                        }
                        $(`#record-start-questionnaire-more`).html(start)
                        $(`#record-end-questionnaire-more`).html(end)

                        $(`#page-num-questionnaire-more`).val(Page)
                     
                        if(Page < paging){

                            $(`#previous-questionnaire-more`).attr('data-page', eval(Page-1));
                            $(`#next-questionnaire-more`).attr('data-page', eval(Page+1));

                            $(`#start-questionnaire-more`).removeClass("disable_click");
                            $(`#previous-questionnaire-more`).removeClass("disable_click");
                            $(`#next-questionnaire-more`).removeClass("disable_click");
                            $(`#end-questionnaire-more`).removeClass("disable_click");

                        }else if(Page = paging){

                            $(`#start-questionnaire-more`).removeClass("disable_click");
                            $(`#previous-questionnaire-more`).removeClass("disable_click");

                            $(`#next-questionnaire-more`).addClass("disable_click");
                            $(`#end-questionnaire-more`).addClass("disable_click");

                            $(`#previous-questionnaire-more`).attr('data-page', eval(Page-1));
                        }
                        //paging
                    }
                    $(`#page-of-questionnaire-more`).html(paging)
                }

                $('.input-pagenumber-questionnaire-more').change(function(i, e){
                    var crmID = $(this).data('questionnaireid')
                    var moduleSelect = $(this).data('moduleselect')
                    var type = $(this).data('type')

                    var Page = $(`#page-num-questionnaire-more`).val()
                    var totalPage = $(`#page-of-questionnaire-more`).html()
                   
                    if(Page > eval(totalPage)){
                        return false
                    }
                    Related_questionnaire_more(moduleSelect,crmID,type,Page)
                })
                $('.overlay').hide();
            }else{
                var rowQuestionnaire = `<div class="list-record-related text-center">
                                    <label class="font-16 p-10">No Data</label>
                                </div>`
                $(`#related-questionnaire`).append(rowQuestionnaire)
                $('.overlay').hide();
            }
        },'json')    
    }
    /*Related*/
    
    $(function() {
        setTimeout(function() {
            $('.overlay').hide();
        }, 1000)

        var setElementHeight = function () {
            var height = $(window).height();

            $('.tab-suummary').css('max-height', (height-100));
            $('.tab-all').css('height', (height-100));
            $('.tab-timeline').css('height', (height-100));
        };  

        $(window).on("resize", function () {
            setElementHeight();
        }).resize();

        var sortOrder = 'asc',
        $toggleSort = $('.toggle-sort');
        $toggleSort.on('click', function() {
            switch (sortOrder) {
              case 'asc':
                sortOrder = 'desc';
                $('.sort-desc').css('display','none');
                $('.sort-asc').css('display','unset');
                break;
              case 'desc':
                sortOrder = 'asc';
                $('.sort-asc').css('display','none');
                $('.sort-desc').css('display','unset');
                break;
            }
        });

        $('.bg-popup').click(function() {

            $('.bg-popup').css('display','none');
            var popup_users = $('#popup_users').css('display');
            if (popup_users === "block") {
                $('#popup_users').css('display', "none")
                $('#popup_users').css('width',"150px")
            }

            var popup_field = $('#popup_field').css('display');
            if (popup_field === "block") {
                $('#popup_field').css('display', "none")
                $('#popup_field').css('width',"200px")
            }

        });
        var params = {moduleSelect, crmID}
        /*Summary Detail*/
        $.post('<?php echo site_url('Projects/getDetailSummary'); ?>', params, function(rs){
            
            $(`#detail-summary`).html('')

            $(rs).each(function( index,value ) {
              if(value['header_name'] == "Projects Information"){
                $(value['form']).each(function(key,val) {

                    if(val['uitype']==53 || val['uitype']==73 || val['uitype']==57){
                        value = val.value_name;
                    }else if(val['uitype']==5){
                        var objectDate = new Date(val.value);
                        var day = objectDate.getDate();
                        var month = objectDate.getMonth();
                        var year = objectDate.getFullYear();
                        value = day+"/"+(month + 1)+"/"+year;
                        //value = ConvertDateformat(val.value);
                    }else if(val['uitype']==56){
                        value = (val.value == 1) ? 'Yes' :'No';
                    }else if(val['uitype']==71){
                        value = val.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        //value = numberWithCommas(val.value);
                    }else if(val['uitype']==301){
                        value = val.value_name;
                    }else{
                        value = val.value;
                    }
                    var rowHtml = `<div class="row mb-5">
                                        <div class="col-lg-3 col-12 m-a">
                                            <label class="pl-5 mb-5 font-14 text-gray-3"><span id="label-bci_no">${val.fieldlabel}</span></label>
                                        </div>
                                        <div class="col-lg-9 col-12 label-${val.columnname}">
                                         ${value}
                                        </div>
                                    </div>`

                    $(`#detail-summary`).append(rowHtml)
                });

              }

            });
        },'json')

        /*Summary Comment*/
        $.post('<?php echo site_url('Projects/getComments'); ?>', params, function(rs){
            $(`#list_comment`).html('')
            $(rs).each(function( index,value ) {
                var rowHtml = `<div class="col-12 mb-5">
                        <div class="card-comment">
                            <div class="comment-status">
                                <label class="font-12">${value.projectorder_status}</label> 
                            </div>
                            <div class="comment-username">
                                <label class="font-14 font-bold ">${value.username}</label> 
                            </div>
                            <div class="comment-message">
                                <label class="font-14 ">${value.comments}</label> 
                            </div>
                        </div>
                    </div>
                    <div class="font-14 comment-timestamp mb-10 ml-10">
                        <label class="font-14">${value.createdtime}</label>
                    </div>`;
                $(`#list_comment`).append(rowHtml)
            });
        },'json')

        /*Summary Related*/
        getRelated(moduleSelect,crmID);
        
        /*Detail*/
        $('#tab-detail').click(function(i){
            $('.overlay').show()
            $.post('<?php echo site_url('Projects/getDetailSummary'); ?>', params, function(rs){
            
                $(`#projects-info`).html('')
                $(`#key-man`).html('')
                $(`#remark`).html('')
                $(`#administrator`).html('')
                $(rs).each(function( index,value ) {
                    if(value.header_name == "Projects Information"){
                        $(value.form).each(function(key,val) {

                            if(val.uitype==53 || val.uitype==73 || val.uitype==57){
                                value = val.value_name;
                            }else if(val.uitype==5){
                                var objectDate = new Date(val.value);
                                var day = objectDate.getDate();
                                var month = objectDate.getMonth();
                                var year = objectDate.getFullYear();
                                value = day+"/"+(month + 1)+"/"+year;
                            }else if(val['uitype']==56){
                                value = (val.value == 1) ? 'Yes' :'No';
                            }else if(val.uitype==71){
                                value = val.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }else{
                                value = val.value;
                            }
                            var rowHtml = `<div class="col-6">
                                        <label class="pl-5 mb-5">
                                            <span class="label-left">${val.fieldlabel} : </span>
                                            <span class="label-right label-${val.columnname}">${value}</span>
                                        </label>
                                    </div>`

                            $(`#projects-info`).append(rowHtml)
                        });
                    }else if(value.header_name == "Key Man Customer Information"){
                        $(value.form).each(function(key,val) {
                            if(val.uitype==53 || val.uitype==73 || val.uitype==57){
                                value = val.value_name;
                            }else if(val.uitype==5){
                                var objectDate = new Date(val.value);
                                var day = objectDate.getDate();
                                var month = objectDate.getMonth();
                                var year = objectDate.getFullYear();
                                value = day+"/"+(month + 1)+"/"+year;
                            }else if(val.uitype==71){
                                value = val.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }else{
                                value = val.value;
                            }
                            var rowHtml = `<div class="col-6">
                                        <label class="pl-5 mb-5">
                                            <span class="label-left">${val.fieldlabel} : </span>
                                            <span class="label-right">${value}</span>
                                        </label>
                                    </div>`

                            $(`#key-man`).append(rowHtml)
                        });
                    }else if(value.header_name == "Remark"){
                        $(value.form).each(function(key,val) {
                            if(val.uitype==53 || val.uitype==73 || val.uitype==57){
                                value = val.value_name;
                            }else if(val.uitype==5){
                                var objectDate = new Date(val.value);
                                var day = objectDate.getDate();
                                var month = objectDate.getMonth();
                                var year = objectDate.getFullYear();
                                value = day+"/"+(month + 1)+"/"+year;
                            }else if(val.uitype==71){
                                value = val.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }else if(val.uitype==19){
                                value = val.value.replace(/(\n|\r)/g,'<br>')
                            }else{
                                value = val.value;
                            }
                            var rowHtml = `<div class="col-6">
                                        <label class="pl-5 mb-5">
                                            <span class="label-left">${val.fieldlabel} : </span>
                                            <span class="label-right">${value}</span>
                                        </label>
                                    </div>`

                            $(`#remark`).append(rowHtml)
                        });
                    }else if(value.header_name == "Administrator Information"){
                        $(value.form).each(function(key,val) {

                           if(val.uitype==5){
                                var objectDate = new Date(val.value);
                                var day = objectDate.getDate();
                                var month = objectDate.getMonth();
                                var year = objectDate.getFullYear();
                                value = day+"/"+(month + 1)+"/"+year;
                            }else if(val['uitype']==70){
                                var dateTime = val.value;
								var parts = dateTime.split(/[- :]/);
								var wanted = `${parts[2]}/${parts[1]}/${parts[0]} ${parts[3]}:${parts[4]}:${parts[5]}`;
                                value = wanted;
                            }else{
                                value = val.value;
                            }
                            var rowHtml = `<div class="col-6">
                                        <label class="pl-5 mb-5">
                                            <span class="label-left">${val.fieldlabel} : </span>
                                            <span class="label-right label-${val.columnname}">${value}</span>
                                        </label>
                                    </div>`

                            $(`#administrator`).append(rowHtml)
                        });
                    }
                });
                $('.overlay').hide()
            },'json')
            
            $.post('<?php echo site_url('Projects/getDetailList'); ?>', params, function(drs){
                $(".row_data").remove();
               
                $(drs).each(function( key,val ) {
                    
                    if(val.owner.length > 0){
                        $(val.owner).each(function( k,v ) {
                            var rowOwner = `<tr id="row_owner${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                    
                                <td>
                                    <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td><input type="text" class="base-input base-input-text" id="owner_no${v.sequence_no}" name="owner_no${v.sequence_no}" readonly value="${v.owner_no}"></td>
                                <td><input type="text" class="base-input base-input-text" id="owner_name_th${v.sequence_no}" name="owner_name_th${v.sequence_no}" readonly value="${v.owner_name_th}"></td>
                                <td><input type="text" class="base-input base-input-text" id="owner_name_en${v.sequence_no}" name="owner_name_en${v.sequence_no}" readonly value="${v.owner_name_en}"></td>
                                <td><input type="text" class="base-input base-input-text" id="owner_group${v.sequence_no}" name="owner_group${v.sequence_no}" readonly value="${v.owner_group}"></td>
                                <td><input type="text" class="base-input base-input-text" id="owner_industry${v.sequence_no}" name="owner_industry${v.sequence_no}" readonly value="${v.owner_industry}"></td>
                                <td><input type="text" class="base-input base-input-text" id="owner_grade${v.sequence_no}" name="owner_grade${v.sequence_no}" readonly value="${v.owner_grade}"></td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="contactname${v.sequence_no}" name="contactname${v.sequence_no}" readonly value="${v.contactname}" title="${v.contactname}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_owner${v.sequence_no}" name="service_level_owner${v.sequence_no}" readonly value="${v.service_level_owner}" title="${v.service_level_owner}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="sales_owner_name${v.sequence_no}" name="sales_owner_name${v.sequence_no}" readonly value="${v.sales_owner_name}" title="${v.sales_owner_name}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_owner${v.sequence_no}" name="percen_com_sales_owner${v.sequence_no}" readonly value="${v.percen_com_sales_owner}" title="${v.percen_com_sales_owner}">
                                </td>
                            </tr>`
                            $(`#proTabOwner`).append(rowOwner)
                        });
                    }

                    if(val.consultant.length > 0){
                        $(val.consultant).each(function( k,v ) {
                            var rowConsultant = `<tr id="row_consul${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                    
                                <td>
                                    <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td><input type="text" class="base-input base-input-text" id="consultant_no${v.sequence_no}" name="consultant_no${v.sequence_no}" readonly value="${v.consultant_no}"></td>
                                <td><input type="text" class="base-input base-input-text" id="consultant_name_th${v.sequence_no}" name="consultant_name_th${v.sequence_no}" readonly value="${v.consultant_name_th}"></td>
                                <td><input type="text" class="base-input base-input-text" id="consultant_name_en${v.sequence_no}" name="consultant_name_en${v.sequence_no}" readonly value="${v.consultant_name_en}"></td>
                                <td><input type="text" class="base-input base-input-text" id="consultant_group${v.sequence_no}" name="consultant_group${v.sequence_no}" readonly value="${v.consultant_group}"></td>
                                <td><input type="text" class="base-input base-input-text" id="consultant_industry${v.sequence_no}" name="consultant_industry${v.sequence_no}" readonly value="${v.consultant_industry}"></td>
                                <td><input type="text" class="base-input base-input-text" id="consultant_grade${v.sequence_no}" name="consultant_grade${v.sequence_no}" readonly value="${v.consultant_grade}"></td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="contactname${v.sequence_no}" name="contactname${v.sequence_no}" readonly value="${v.contactname}" title="${v.contactname}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_consultant${v.sequence_no}" name="service_level_consultant${v.sequence_no}" readonly value="${v.service_level_consultant}" title="${v.service_level_consultant}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="sales_consultant_name${v.sequence_no}" name="sales_consultant_name${v.sequence_no}" readonly value="${v.sales_consultant_name}" title="${v.sales_consultant_name}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_consultant${v.sequence_no}" name="percen_com_sales_consultant${v.sequence_no}" readonly value="${v.percen_com_sales_consultant}" title="${v.percen_com_sales_consultant}">
                                </td>
                            </tr>`
                            $(`#proTabConsultant`).append(rowConsultant)
                        });
                    }

                    if(val.architecture.length > 0){
                        $(val.architecture).each(function( k,v ) {
                            var rowArchitecture = `<tr id="row_arc${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td><input type="text" class="base-input base-input-text" id="architecture_no${v.sequence_no}" name="architecture_no${v.sequence_no}" readonly value="${v.architecture_no}"></td>
                                <td><input type="text" class="base-input base-input-text" id="architecture_name_th${v.sequence_no}" name="architecture_name_th${v.sequence_no}" readonly value="${v.architecture_name_th}"></td>
                                <td><input type="text" class="base-input base-input-text" id="architecture_name_en${v.sequence_no}" name="architecture_name_en${v.sequence_no}" readonly value="${v.architecture_name_en}"></td>
                                <td><input type="text" class="base-input base-input-text" id="architecture_group${v.sequence_no}" name="architecture_group${v.sequence_no}" readonly value="${v.architecture_group}"></td>
                                <td><input type="text" class="base-input base-input-text" id="architecture_industry${v.sequence_no}" name="architecture_industry${v.sequence_no}" readonly value="${v.architecture_industry}"></td>
                                <td><input type="text" class="base-input base-input-text" id="architecture_grade${v.sequence_no}" name="architecture_grade${v.sequence_no}" readonly value="${v.architecture_grade}"></td>
                                <td>
                                   <input type="text" class="base-input base-input-text" id="contactname${v.sequence_no}" name="contactname${v.sequence_no}" readonly value="${v.contactname}" title="${v.contactname}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_architecture${v.sequence_no}" name="service_level_architecture${v.sequence_no}" readonly value="${v.service_level_architecture}" title="${v.service_level_architecture}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="sales_architecture_name${v.sequence_no}" name="sales_architecture_name${v.sequence_no}" readonly value="${v.sales_architecture_name}" title="${v.sales_architecture_name}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_architecture${v.sequence_no}" name="percen_com_sales_architecture${v.sequence_no}" readonly value="${v.percen_com_sales_architecture}" title="${v.percen_com_sales_architecture}">
                                </td>
                            </tr>`
                            $(`#proTabArchitec`).append(rowArchitecture)
                        });
                    }

                    if(val.construction.length > 0){
                        $(val.construction).each(function( k,v ) {
                            var rowConstruction = `<tr id="row_const${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td><input type="text" class="base-input base-input-text" id="construction_no${v.sequence_no}" name="construction_no${v.sequence_no}" readonly value="${v.construction_no}"></td>
                                <td><input type="text" class="base-input base-input-text" id="construction_name_th${v.sequence_no}" name="construction_name_th${v.sequence_no}" readonly value="${v.construction_name_th}"></td>
                                <td><input type="text" class="base-input base-input-text" id="construction_name_en${v.sequence_no}" name="construction_name_en${v.sequence_no}" readonly value="${v.construction_name_en}"></td>
                                <td><input type="text" class="base-input base-input-text" id="construction_group${v.sequence_no}" name="construction_group${v.sequence_no}" readonly value="${v.construction_group}"></td>
                                <td><input type="text" class="base-input base-input-text" id="construction_industry${v.sequence_no}" name="construction_industry${v.sequence_no}" readonly value="${v.construction_industry}"></td>
                                <td><input type="text" class="base-input base-input-text" id="construction_grade${v.sequence_no}" name="construction_grade${v.sequence_no}" readonly value="${v.construction_grade}"></td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="contactname${v.sequence_no}" name="contactname${v.sequence_no}" readonly value="${v.contactname}" title="${v.contactname}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_construction${v.sequence_no}" name="service_level_construction${v.sequence_no}" readonly value="${v.service_level_construction}" title="${v.service_level_construction}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="sales_construction_name${v.sequence_no}" name="sales_construction_name${v.sequence_no}" readonly value="${v.sales_construction_name}" title="${v.sales_construction_name}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_construction${v.sequence_no}" name="percen_com_sales_construction${v.sequence_no}" readonly value="${v.percen_com_sales_construction}" title="${v.percen_com_sales_construction}">
                                </td>
                            </tr>`
                            $(`#proTabConstruction`).append(rowConstruction)
                        });
                    }

                    if(val.designer.length > 0){
                        $(val.designer).each(function( k,v ) {

                            var rowDesigner = `<tr id="row_designer${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td><input type="text" class="base-input base-input-text" id="designer_no${v.sequence_no}" name="designer_no${v.sequence_no}" readonly value="${v.designer_no}"></td>
                                <td><input type="text" class="base-input base-input-text" id="designer_name_th${v.sequence_no}" name="designer_name_th${v.sequence_no}" readonly value="${v.designer_name_th}"></td>
                                <td><input type="text" class="base-input base-input-text" id="designer_name_en${v.sequence_no}" name="designer_name_en${v.sequence_no}" readonly value="${v.designer_name_en}"></td>
                                <td><input type="text" class="base-input base-input-text" id="designer_group${v.sequence_no}" name="designer_group${v.sequence_no}" readonly value="${v.designer_group}"></td>
                                <td><input type="text" class="base-input base-input-text" id="designer_industry${v.sequence_no}" name="designer_industry${v.sequence_no}" readonly value="${v.designer_industry}"></td>
                                <td><input type="text" class="base-input base-input-text" id="designer_grade${v.sequence_no}" name="designer_grade${v.sequence_no}" readonly value="${v.designer_grade}"></td>
                                <td>
                                   <input type="text" class="base-input base-input-text" id="contactname${v.sequence_no}" name="contactname${v.sequence_no}" readonly value="${v.contactname}" title="${v.contactname}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_designer${v.sequence_no}" name="service_level_designer${v.sequence_no}" readonly value="${v.service_level_designer}" title="${v.service_level_designer}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="sales_designer_name${v.sequence_no}" name="sales_designer_name${v.sequence_no}" readonly value="${v.sales_designer_name}" title="${v.sales_designer_name}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_designer${v.sequence_no}" name="percen_com_sales_designer${v.sequence_no}" readonly value="${v.percen_com_sales_designer}" title="${v.percen_com_sales_designer}">
                                </td>
                            </tr>`
                            $(`#proTabDesigner`).append(rowDesigner)
                        });
                    }

                    if(val.contractor.length > 0){
                        $(val.contractor).each(function( k,v ) {
                            
                            var rowContractor = `<tr id="row_contractor${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td><input type="text" class="base-input base-input-text" id="contractor_no${v.sequence_no}" name="contractor_no${v.sequence_no}" readonly value="${v.contractor_no}"></td>
                                <td><input type="text" class="base-input base-input-text" id="contractor_name_th${v.sequence_no}" name="contractor_name_th${v.sequence_no}" readonly value="${v.contractor_name_th}"></td>
                                <td><input type="text" class="base-input base-input-text" id="contractor_name_en${v.sequence_no}" name="contractor_name_en${v.sequence_no}" readonly value="${v.contractor_name_en}"></td>
                                <td><input type="text" class="base-input base-input-text" id="contractor_group${v.sequence_no}" name="contractor_group${v.sequence_no}" readonly value="${v.contractor_group}"></td>
                                <td><input type="text" class="base-input base-input-text" id="contractor_industry${v.sequence_no}" name="contractor_industry${v.sequence_no}" readonly value="${v.contractor_industry}"></td>
                                <td><input type="text" class="base-input base-input-text" id="contractor_grade${v.sequence_no}" name="contractor_grade${v.sequence_no}" readonly value="${v.contractor_grade}"></td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="contactname${v.sequence_no}" name="contactname${v.sequence_no}" readonly value="${v.contactname}" title="${v.contactname}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_contractor${v.sequence_no}" name="service_level_contractor${v.sequence_no}" readonly value="${v.service_level_contractor}" title="${v.service_level_contractor}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="sales_contractor_name${v.sequence_no}" name="sales_contractor_name${v.sequence_no}" readonly value="${v.sales_contractor_name}" title="${v.sales_contractor_name}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_contractor${v.sequence_no}" name="percen_com_sales_contractor${v.sequence_no}" readonly value="${v.percen_com_sales_contractor}" title="${v.percen_com_sales_contractor}">
                                </td>
                            </tr>`
                            $(`#proTabContractor`).append(rowContractor)
                        });
                    }

                    if(val.subcontractor.length > 0){
                        $(val.subcontractor).each(function( k,v ) {
                            
                            var rowSubContractor = `<tr id="row_subcontractor${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_sub_contractor${v.sequence_no}" name="service_level_sub_contractor${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_no${v.sequence_no}" name="sub_contractor_no${v.sequence_no}" readonly value="${v.sub_contractor_no}"></td>
                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_th${v.sequence_no}" name="sub_contractor_name_th${v.sequence_no}" readonly value="${v.sub_contractor_name_th}"></td>
                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_en${v.sequence_no}" name="sub_contractor_name_en${v.sequence_no}" readonly value="${v.sub_contractor_name_en}"></td>
                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_group${v.sequence_no}" name="sub_contractor_group${v.sequence_no}" readonly value="${v.sub_contractor_group}"></td>
                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_industry${v.sequence_no}" name="sub_contractor_industry${v.sequence_no}" readonly value="${v.sub_contractor_industry}"></td>
                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_grade${v.sequence_no}" name="sub_contractor_grade1${v.sequence_no}" readonly value="${v.sub_contractor_grade}"></td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="contactname${v.sequence_no}" name="contactname${v.sequence_no}" readonly value="${v.contactname}" title="${v.contactname}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="service_level_sub_contractor${v.sequence_no}" name="service_level_sub_contractor${v.sequence_no}" readonly value="${v.service_level_sub_contractor}" title="${v.service_level_sub_contractor}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="sales_sub_contractor_name${v.sequence_no}" name="sales_sub_contractor_name${v.sequence_no}" readonly value="${v.sales_sub_contractor_name}" title="${v.sales_sub_contractor_name}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_sub_contractor${v.sequence_no}" name="percen_com_sales_sub_contractor${v.sequence_no}" readonly value="${v.percen_com_sales_sub_contractor}" title="${v.percen_com_sales_sub_contractor}">
                                </td>
                            </tr>`
                            $(`#proTabSubContractor`).append(rowSubContractor)
                        });
                    }

                    /*Products Inventory*/
                    var total_est = 0
                    var total_plan = 0
                    var total_deli = 0
                    var total_on_hand = 0
                    var sum_onhand_total = 0
                    if(val.products.length > 0){
                        
                        $(val.products).each(function( k,v ) {
                            var DateStart = '';
                            var DateEnd = '';

                            if(v.first_delivered_date != '1970-01-01' && v.first_delivered_date != '0000-00-00'){
                                var objectDate = new Date(v.first_delivered_date);
                                var day = objectDate.getDate();
                                var month = objectDate.getMonth();
                                var year = objectDate.getFullYear();
                                DateStart = day+"/"+(month + 1)+"/"+year;
                            }
                            if(v.last_delivered_date != '1970-01-01' && v.last_delivered_date != '0000-00-00'){
                                var objectDate = new Date(v.last_delivered_date);
                                var day = objectDate.getDate();
                                var month = objectDate.getMonth();
                                var year = objectDate.getFullYear();
                                DateEnd = day+"/"+(month + 1)+"/"+year;
                            }
                            
                            var onhand_total = v.remain_on_hand * v.listprice
                            sum_onhand_total = sum_onhand_total + onhand_total

                            var rowProducts = `<tr id="row${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                    <input type="hidden" name="item_productid" value="${v.productid}" />
                                </td>
                                <td>
                                    <div class="mb-2">
                                        <input type="text" class="base-input base-input-text" id="productname${v.sequence_no}" name="productname${v.sequence_no}" readonly value="${v.productname}" title="${v.productname}">
                                        <textarea class="base-input base-input-text mt-5" id="descriptions${v.sequence_no}" name="descriptions${v.sequence_no}" rows="2" readonly  title="${v.comment}">${v.comment}</textarea>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="product_brand${v.sequence_no}" name="product_brand${v.sequence_no}" readonly value="${v.product_brand}" title="${v.product_brand}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="product_group${v.sequence_no}" name="product_group${v.sequence_no}" readonly value="${v.product_group}" title="${v.product_group}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                                </td>
                                <td style="display: flex;text-align: center;">
                                    <div role="button" class="btn-p" onclick="$.addProductPlan('${v.productid}','${v.id}','${v.lineitem_id}')">P</div>
                                    <div role="button" class="btn-d" onclick="$.addProductDelivered('${v.productid}','${v.id}','${v.accountname}','${v.accountid}','${v.lineitem_id}')">D</div>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text first_delivered-${v.lineitem_id}" id="first_delivered_date${v.sequence_no}" name="first_delivered_date${v.sequence_no}" readonly value="${DateStart}" title="${DateStart}"> 
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text last_delivered-${v.lineitem_id}" id="last_delivered_date${v.sequence_no}" name="last_delivered_date${v.sequence_no}" readonly value="${DateEnd}" title="${DateEnd}"> 
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text estimated estimated-${v.lineitem_id}" id="estimated${v.sequence_no}" name="estimated${v.sequence_no}" readonly value="${v.estimated.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.estimated.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text plan plan-${v.lineitem_id}" id="plan${v.sequence_no}" name="plan${v.sequence_no}" readonly value="${v.plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text delivered delivered-${v.lineitem_id}" id="delivered${v.sequence_no}" name="delivered${v.sequence_no}" readonly value="${v.delivered.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.delivered.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text remain_on_hand remain_on_hand-${v.lineitem_id}" id="remain_on_hand${v.sequence_no}" name="remain_on_hand${v.sequence_no}" readonly value="${v.remain_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.remain_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="listprice${v.sequence_no}" name="listprice${v.sequence_no}" readonly value="${v.listprice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.listprice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="onhand_total${v.sequence_no}" name="onhand_total${v.sequence_no}" readonly value="${onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                            </tr>`
                            
                            $(`#proTab > tbody`).append(rowProducts)

                            total_est += eval(v.estimated);
                            total_plan += eval(v.plan);
                            total_deli += eval(v.delivered);
                            total_on_hand += eval(v.remain_on_hand);
                        });
                    }
                    var rowProducts = `<tr id="row" class="row_data">
                            <td class="txt-center txt-middle"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="v-align-middle" align="right">
                                <span class="font-16 font-bold">Total</span></td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="total_est" name="total_est" readonly value="${total_est.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_est.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="total_plan" name="total_plan" readonly value="${total_plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="total_deli" name="total_deli" readonly value="${total_deli.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_deli.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="total_on_hand" name="total_on_hand" readonly value="${total_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td></td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="sum_onhand_total" name="sum_onhand_total" readonly value="${sum_onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${sum_onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                        </tr>`
                    $(`#proTab > tbody`).append(rowProducts)
                    /*Products Inventory*/

                    if(val.competitor.length > 0){
                        $(val.competitor).each(function( k,v ) {
                            
                            var rowCompetitor = `<tr id="row_com${v.sequence_no}" class="row_data">
                                <td class="txt-center txt-middle">
                                    <label>${v.sequence_no}</label>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="competitorproduct_name${v.sequence_no}" name="competitorproduct_name${v.sequence_no}" readonly value="${v.competitorproduct_name_th}" title="${v.competitorproduct_name_th}">
                                    <div class="mb-2">
                                        <textarea class="base-input base-input-text mt-5" id="descriptions_com${v.sequence_no}" name="descriptions_com${v.sequence_no}" rows="2" title="${v.competitorcomment}" readonly>${v.competitorcomment}</textarea>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="competitor_brand${v.sequence_no}" name="competitor_brand${v.sequence_no}" readonly value="${v.competitor_brand}" title="${v.competitor_brand}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="comprtitor_product_group${v.sequence_no}" name="comprtitor_product_group${v.sequence_no}" readonly value="${v.comprtitor_product_group}" title="${v.comprtitor_product_group}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="comprtitor_product_size${v.sequence_no}" name="comprtitor_product_size${v.sequence_no}" readonly value="${v.comprtitor_product_size}" title="${v.comprtitor_product_size}">
                                </td>

                                <td>
                                   <input type="text" class="base-input base-input-text" id="comprtitor_product_thickness${v.sequence_no}" name="comprtitor_product_thickness${v.sequence_no}" readonly value="${v.comprtitor_product_thickness}" title="${v.comprtitor_product_thickness}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="comprtitor_estimated_unit${v.sequence_no}" name="comprtitor_estimated_unit${v.sequence_no}" readonly value="${v.comprtitor_estimated_unit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.comprtitor_estimated_unit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                                <td>
                                    <input type="text" class="base-input base-input-text" id="competitor_price${v.sequence_no}" name="competitor_price${v.sequence_no}" readonly value="${v.competitor_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.competitor_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                                </td>
                            </tr>`
                            $(`#proTabCom`).append(rowCompetitor)
                        });
                    }

                });

                
            },'json')
        })

        /*Related*/
        $('#tab-related').click(function(i){
            $('.overlay').show()
            Related_visit_more(moduleSelect,crmID,'related')
            Related_quotation_more(moduleSelect,crmID,'related')
            Related_documents_more(moduleSelect,crmID,'related')
            Related_pricelist_more(moduleSelect,crmID,'related')
            Related_samplerequisition_more(moduleSelect,crmID,'related')
            Related_expenses_more(moduleSelect,crmID,'related')
            Related_questionnaire_more(moduleSelect,crmID,'related')
        })

        /*Timeline*/
        $('#tab-timeline').click(function(i){
            $('.overlay').show()
            $.post('<?php echo site_url('Projects/getTimeline'); ?>', params, function(rs){
                
                $('#timelineList').html('')
                var datetimeline = '';
                $(rs.timeline).each(function( index,value ) {
                    
                    if(datetimeline !== value.date_create){
                        rowDatecreate = `<div class="middle-bar mix date-timeline" data-filter="${index}"> ${value.date_create} </div>`
                        $('#timelineList').append(rowDatecreate)
                        datetimeline = value.date_create
                    }
                    
                    if(value.profile_image != ''){
                         var img_profile = '../../../'+value.profile_image
                    }else{
                        var img_profile = '../../../themes/softed/images/profile.png';
                    }

                    if(value.action == 'edit'){
                        rowTimeline = `<div class="steps-container mix ${value.fieldid} ${value.user_name} timelineRecord" data-filter="${index}">
                            <div class="content">
                              <span class="float-right time">${value.time_create}</span>
                              <h2>${value.user_name}</h2>
                              <p class="txt-update">Update the<span class="field-update"> ${value.fieldlabel}</span></p> 
                              <p class="txt-update-">${value.old_value}<span class="field-update"> > ${value.new_value}</span></p> 
                            </div>
                            <i class="step-line"></i>
                            <div class="date">
                                <img src="${img_profile}">
                            </div>
                          </div>`
                        $('#timelineList').append(rowTimeline);
                    }else if(value.action == 'create'){
                        rowTimeline = `<div class="steps-container mix ${value.fieldid} ${value.user_name} timelineRecord" data-filter="${index}">
                            <div class="content">
                              <span class="float-right time">${value.time_create}</span>
                              <h2>${value.user_name}</h2>
                              <p class="txt-update">Create the<span class="field-update"> ${moduleSelect}</span></p> 
                            </div>
                            <i class="step-line"></i>
                            <div class="date">
                                <img src="${img_profile}">
                            </div>
                          </div>`
                        $('#timelineList').append(rowTimeline);
                    }
                    
                });

                $('#usersUL').html('')                
                $(rs.users).each(function( index,value ) {
                    rowUsers = `<label class="container2">
                            <span>${value.user_name}</span>
                            <input type="checkbox" name="nameuser" value="${value.user_name}" checked/>
                            <span class="checkmark"></span>
                        </label>`
                    $('#usersUL').append(rowUsers);
                });

                $('#fieldUL').html('')
                $(rs.field).each(function( index,value ) {
                    rowField = `<label class="container2">
                            <span title="${value.fieldlabel}">${value.fieldlabel}</span>
                            <input type="checkbox" name="namefield" value="${value.fieldid}" checked/>
                            <span class="checkmark"></span>
                        </label>`
                    $('#fieldUL').append(rowField);
                });

                var containerEl = document.querySelector('.timelineList');
                var mixer = mixitup(containerEl);

                $('input[name="namefield"]').click(function() {
                    var namefield;
                    //$('.timelineRecord').css('display','none');
                    $('.timelineRecord').fadeOut();
                    $.each($("input[name='namefield']:checked"), function(){            
                        namefield = $(this).val();
                        //$('.'+namefield).css('display','flex');
                        $('.'+namefield).fadeIn();
                    });
                });

                $('input[name="nameuser"]').click(function() {
                    var nameuser;
                    //$('.timelineRecord').css('display','none');
                    $('.timelineRecord').fadeOut();
                    $.each($("input[name='nameuser']:checked"), function(){            
                        nameuser = $(this).val();
                        //$('.'+nameuser).css('display','flex');
                        $('.'+nameuser).fadeIn();
                    });
                });

                $('.overlay').hide()
            },'json')                   
        })
        
        $.ResetFillter = function() {
            $( "input[name='namefield']" ).prop( "checked", true );
            $( "input[name='nameuser']" ).prop( "checked", true );
            $('.timelineRecord').css('display','flex');
        }

        $('.select-satus').change(function(i, e){
            var status = $('#select-satus').find(":selected").val()

            if(status === 'Cancelled'){
                var modalBody = `<div class="row">
                    <div>เหตุผลการยกเลิก <span class="text-danger">*</span></div>
                    <div>
                        <textarea class="form-control" id="cancel_reason"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2 p-5">
                        <button type="button" class="btn btn-default" onclick="$.closeModal()">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="$.saveCancel()">Save</button>
                    </div>
                </div>`
                $('#modal-dialog .modal-body').html(modalBody)
                $('#modal-dialog').modal({
                        backdrop: 'static',
                        keyboard: true
                })
                $('#modal-dialog').modal('show')
            } else {
                $('.overlay').show()
                $.updateStatus({crmid:crmID,projectorder_status:status,action:'edit'})
            }

            
        })

        $.closeModal = function(){
            $('#modal-dialog').modal('hide')
            $('#modal-dialog .modal-body').html('')
        }

        $.saveCancel = function(){
            var cancelReason = $('#cancel_reason').val()
            if(cancelReason != ''){
                $('#cancel_reason').removeClass('border-danger')
                var status = $('#select-satus').find(":selected").val()
                $('.overlay').show()
                $.updateStatus({crmid:crmID, projectorder_status:status, cancelReason, action:'edit'})
            } else {
                $('#cancel_reason').addClass('border-danger')
            }
        }

        $.updateStatus = function(data){
            $.post('<?php echo site_url('Projects/updateStatus?userid='.$this->session->userdata('userID')); ?>', data, function(rs){
                if(rs.status === 'Success'){
                    if(data.projectorder_status === 'Cancelled') $.closeModal();
                    $(`#projectorder_status`).val(status)
                    $(`.label-projectorder_status`).html(status)
                    $('.overlay').hide();
                } else {
                    $('.overlay').hide();
                    Swal.fire('', rs.message, 'error')
                }
            },'json')
        }

        $(".modal-product .product-closed").click(function(i){
            $('.modal-product').modal('hide');
        })

        $.addProductPlan = function(productid,id,lineitem_id) {
            $('#plan-productid').val(productid);
            $('#plan-projectid').val(id);
            $('#plan-lineitem_id').val(lineitem_id);

            $('#d-projectno').val('')
            $('#d-projectname').val('')
            $('#d-productno').val('')
            $('#d-productname').val('')
            $('#d-estimate-qty').val('0')
            $('#d-toatl-qty').val('0')
            $('.list-productplan').html('')
            
            $('.overlay').show();

            $.post('<?php echo site_url('Projects/getProductPlan?userid='.$this->session->userdata('userID')); ?>', {crmid:id,productid:productid,moduleSelect:moduleSelect,action:'view'}, function(rs){

                $('#d-projectno').val(rs.data[0].projects_no)
                $('#d-projectname').val(rs.data[0].projects_name)
                $('#d-productno').val(rs.data[0].product_no)
                $('#d-productname').val(rs.data[0].productname)
                $('#d-estimate-qty').val(rs.data[0].estimated)
                var t_plan = 0;
                $(rs.plan).each(function( key,val ) {
                    var rowPlan = `<div class="card-productplan mb-5 plan-${val.lineitem_id}">
                        <div class="row">
                            <div class="col-5">${val.date_plan}</div>
                            <div class="col-5 plan-qty-${val.lineitem_id}">${val.qty}</div>
                            <div class="col-2">
                                <i class="ph-light ph-note-pencil icon-detail" onclick="$.editProductPlan('${val.date_plan}','${val.qty}','${val.lineitem_id}');" role="button" title="Edit"></i>
                                <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductPlan('${val.lineitem_id}');" role="button" title="Delete"></i>
                            </div>
                        </div>
                    </div>`
                    $(`.list-productplan`).append(rowPlan)

                   t_plan += eval(val.qty);
                });
                $('#d-toatl-qty').val(t_plan)
                $('.overlay').hide();
            },'json')

            $('#myModal-product-plan').modal('show').find('.modal-body');
            
        }

        $.editProductPlan = function(date_plan,qty,lineitem_id) {
            $('#product_plan_date').val(date_plan);
            $('#product_qty').val(qty);
            $('#Planlineitem_id').val(lineitem_id);
        }

        $.deletedProductPlan = function(lineitem_id) {
            $('.overlay').show();
            if (confirm('Are you sure you want to delete this item?')) {
                
                var productid = $('#plan-productid').val()
                var projectid = $('#plan-projectid').val()
                var lineitem = $('#plan-lineitem_id').val()
                
                $.post('<?php echo site_url('Projects/delProductPlan?userid='.$this->session->userdata('userID')); ?>', {crmid:projectid,lineitem_id:lineitem_id,moduleSelect:moduleSelect,productid:productid,lineitem:lineitem,action:'del'}, function(rs){
                    if(rs.status === 'Success'){
                        qty = (rs.qty === null) ? 0 : rs.qty;
                        $('#d-toatl-qty').val(qty)
                        calculate_plan_total(qty,lineitem);
                        $('.plan-'+lineitem_id).remove();
                        $('.overlay').hide();
                    } else {
                        $('.overlay').hide();
                        Swal.fire('', rs.message, 'error')
                    }

                },'json')            
            }else{
                $('.overlay').hide();
            }
        
        }

        $.addProductDelivered = function(productid,id,accountname,accountid,lineitem_id) {
            $('#delivered-productid').val(productid);
            $('#delivered-projectid').val(id);
            $('#delivered-lineitem_id').val(lineitem_id);

            $('#input-dealerid1').val('')
            $('#dealerid1').val('')
            $('#d-delivered-projectno').val('')
            $('#d-delivered-projectname').val('')
            $('#d-delivered-productno').val('')
            $('#d-delivered-productname').val('')
            $('#toatl-delivery-qty').val(0)
            $('#toatl-plan-qty').val(0)
            $('.list-productdelivered').html('')

            $('#input-dealerid1').val(accountname)
            $('#dealerid1').val(accountid)
            $('.overlay').show();
            $.post('<?php echo site_url('Projects/getProductDelivered?userid='.$this->session->userdata('userID')); ?>', {crmid:id,productid:productid,moduleSelect:moduleSelect,action:'view'}, function(rs){
                
                $('#d-delivered-projectno').val(rs.data[0].projects_no)
                $('#d-delivered-projectname').val(rs.data[0].projects_name)
                $('#d-delivered-productno').val(rs.data[0].product_no)
                $('#d-delivered-productname').val(rs.data[0].productname)
                $('#toatl-delivery-qty').val(rs.data[0].delivered)
                $('#toatl-plan-qty').val(rs.data[0].plan)

                var t_delivered = 0;
                $(rs.deliver).each(function( key,val ) {
                    var rowDeliver = `<div class="card-productdelivered mb-5 delivered-${val.lineitem_id}">
                        <div class="row">
                            <div class="col-4" title="${val.accountname}">${cha_length(val.accountname,20)}</div>
                            <div class="col-4">${val.deliver_date}</div>
                            <div class="col-2 deliver-qty-${val.lineitem_id}">${val.qty}</div>
                            <div class="col-2">
                                <i class="ph-light ph-note-pencil icon-detail" onclick="$.editProductDelivered('${val.deliver_date}','${val.qty}','${val.accountname}','${val.accountid}','${val.lineitem_id}');" role="button"></i>
                                <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductDelivered('${val.lineitem_id}');" role="button"></i>
                            </div>
                        </div>
                    </div>`
                    $(`.list-productdelivered`).append(rowDeliver)

                   t_delivered += eval(val.qty);
                });
                $('#toatl-delivery-qty').val(t_delivered)
                $('.overlay').hide();
            },'json')

            $('#myModal-product-delivered').modal('show').find('.modal-body');
        }

        $.editProductDelivered = function(date_delivered,qty,accountname,accountid,lineitem_id) {
            $('#product_delivered_date').val(date_delivered);
            $('#product_delivered_qty').val(qty);
            $('#input-dealerid1').val(accountname);
            $('#dealerid1').val(accountid);
            $('#deliveredlineitem_id').val(lineitem_id);
        }

        $.deletedProductDelivered = function(lineitem_id) {
            $('.overlay').show();
            if (confirm('Are you sure you want to delete this item?')) {
                
                var productid = $('#delivered-productid').val()
                var projectid = $('#delivered-projectid').val()
                var lineitem = $('#delivered-lineitem_id').val()

                $.post('<?php echo site_url('Projects/delProductDelivered?userid='.$this->session->userdata('userID')); ?>', {crmid:projectid,lineitem_id:lineitem_id,moduleSelect:moduleSelect,productid:productid,lineitem:lineitem,action:'del'}, function(rs){
                    if(rs.status === 'Success'){
                        //console.log(rs.delivered_date);
                        qty = (rs.qty === null) ? 0 : rs.qty;
                        $('#toatl-delivery-qty').val(qty)
                        calculate_delivery_total(qty,lineitem,rs.delivered_date,rs.clear)
                        $('.delivered-'+lineitem_id).remove();
                        $('.overlay').hide();
                    } else {
                        $('.overlay').hide();
                        Swal.fire('', rs.message, 'error')
                    }

                },'json')            
            }else{
                $('.overlay').hide();
            }
        
        }

        $.addComments = function() {
            var message = $('#message-comment').val()
            var status = $('#projectorder_status').val()
            if ($.trim(message)=='') {
                $('#message-comment').focus()
                $('#box-comment').addClass('border-focus');
                return false;
            }
            $('.overlay').show()
            $.post('<?php echo site_url('Projects/addComment?userid='.$this->session->userdata('userID')); ?>', {crmid:crmID,message:message,projectorder_status:status,action:'edit'}, function(rs){
                if (rs.status) {
                    $('.overlay').hide();
                    var rowComment = `<div class="col-12 mb-5">
                            <div class="card-comment">
                                <div class="comment-status">
                                    <label class="font-12">${rs.data.projectorder_status}</label> 
                                </div>
                                <div class="comment-username">
                                    <label class="font-14 font-bold ">${rs.data.username}</label> 
                                </div>
                                <div class="comment-message">
                                    <label class="font-14 ">${rs.data.comments}</label> 
                                </div>
                            </div>
                        </div>
                        <div class="font-14 comment-timestamp mb-10 ml-10">
                            <label class="font-14">${rs.data.createdtime}</label>
                        </div>`;
                    $('#message-comment').val('');
                    $("#list_comment").first().prepend(rowComment);

                } else {
                    $('.overlay').hide();
                    Swal.fire('', rs.message, 'error')
                }
            },'json')
            $('#box-comment').removeClass('border-focus');
        }
        
        $.getReport = function() {
            window.open('<?php echo site_url('Projects/viewReport/' . $crmID . '?userid=' . $userID . '&action=viewReport'); ?>', '_blank')
            // window.open('<?php echo site_url('Projects/viewReport/' . $crmID . '?userid=' . $userID . '&action=viewReport'); ?>', '_blank')

        }
        
        $.duplicate = function() {
            $('.overlay').show()
            window.location.href = `<?php echo site_url('Projects/duplicate_web'); ?>/${crmID}?userid=${userID}`  
        }
        
        $.editRecord = function() {
            $('.overlay').show()
            window.location.href = `<?php echo site_url('Projects/edit_web'); ?>/${crmID}?userid=${userID}`  
        }

        $.deleteRecord = function() {
            Swal.fire({
                title: '',
                html: "คุณต้องการ<br/>ลบรายการนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e97126',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'ใช่',
                cancelButtonText: '<span style="color:#e97126">ไม่ใช่</span?',
            }).then((result) => {

                if (result.isConfirmed) {
                    $.post('<?php echo site_url('Projects/delete'); ?>', {
                        crmID,
                        userID
                    }, function(rs) {
                        if (rs.status) {
                            Swal.fire('', 'ลบรายการสำเร้จ', 'success')
                            window.location.href = '<?php echo site_url('Projects/create_web?userid=' . $this->session->userdata('userID')); ?>'
                        } else {
                            Swal.fire('', 'Update Error', 'error')
                        }
                    }, 'json')
                }
            })
        }

        $('#form-productplan').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serializeObject()
            var productid = $('#plan-productid').val()
            var projectid = $('#plan-projectid').val()
            var lineitem = $('#plan-lineitem_id').val()
            var Planlineitem_id = $('#Planlineitem_id').val()

            for (var key in formData){
                var required = $(`#${key}`).prop('required')
                var fieldLabel = $(`#label-${key}`).html()
                var fieldValue = formData[key]

                $(`#${key}`).removeClass('input-error')
                if(required && (fieldValue==='' || fieldValue==='--None--')){
                    $(`#${key}`).addClass('input-error').focus()
                    return false;
                }
            }
            if(Planlineitem_id != ''){
                formData.action = 'edit'
            }else{
                formData.action = 'add'
            }
            
            formData.productid = productid
            formData.projectid = projectid
            formData.lineitem = lineitem
            
            //$('.overlay').show();
            $.post('<?php echo site_url('Projects/savePlan?userid='.$this->session->userdata('userID')); ?>', formData, function(rs){
                if(rs.status === 'Success'){
                    if(formData.action == 'edit'){
                        /**/
                        var qtyedit = $(`.plan-qty-${rs.plan.lineitem_id}`).html()
                    
                        var toatlqty = eval($('#d-toatl-qty').val());
                        var toatlqty = toatlqty-eval(qtyedit);
                    
                        /**/
                        $(`.plan-${rs.plan.lineitem_id}`).html('');
                        var rowEditPlan = `
                            <div class="row">
                                <div class="col-5">${rs.plan.date_plan}</div>
                                <div class="col-5 plan-qty-${rs.plan.lineitem_id}">${rs.plan.qty}</div>
                                <div class="col-2">
                                    <i class="ph-light ph-note-pencil icon-detail" onclick="$.editProductPlan('${rs.plan.date_plan}','${rs.plan.qty}','${rs.plan.lineitem_id}');" role="button" title="Edit"></i>
                                    <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductPlan('${rs.plan.lineitem_id}');" role="button" title="Delete"></i>
                                </div>
                            </div>`
                        $(`.plan-${rs.plan.lineitem_id}`).html(rowEditPlan)

                        $('#d-toatl-qty').val(eval(toatlqty)+eval(rs.plan.qty));
                        var qty =eval(toatlqty)+eval(rs.plan.qty);

                    }else{
                        var rowSavePlan = `<div class="card-productplan mb-5 plan-${rs.plan.lineitem_id}">
                            <div class="row">
                                <div class="col-5">${rs.plan.date_plan}</div>
                                <div class="col-5 plan-qty-${rs.plan.lineitem_id}">${rs.plan.qty}</div>
                                <div class="col-2">
                                    <i class="ph-light ph-note-pencil icon-detail" onclick="$.editProductPlan('${rs.plan.date_plan}','${rs.plan.qty}','${rs.plan.lineitem_id}');" role="button" title="Edit"></i>
                                    <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductPlan('${rs.plan.lineitem_id}');" role="button" title="Delete"></i>
                                </div>
                            </div>
                        </div>`
                        $(".list-productplan").first().prepend(rowSavePlan);

                        var toatlqty = eval($('#d-toatl-qty').val());
                        $('#d-toatl-qty').val(eval(toatlqty)+eval(rs.plan.qty));
                        var qty =eval(toatlqty)+eval(rs.plan.qty);
                    }
                    
                    calculate_plan_total(qty,lineitem)
                    
                    $('#product_qty').val('')
                    $('#product_plan_date').val('')
                    $('#Planlineitem_id').val('')
                    $('.overlay').hide();
                } else {
                    $('.overlay').hide();
                    Swal.fire('', rs.message, 'error')
                }
            },'json')        
        })

        $('#form-product-delivered').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var formData = form.serializeObject()
            var productid = $('#delivered-productid').val()
            var projectid = $('#delivered-projectid').val()
            var accountid = $('#dealerid1').val()
            var lineitem = $('#delivered-lineitem_id').val()
            var deliveredlineitem_id = $('#deliveredlineitem_id').val()

            for (var key in formData){
                var required = $(`#${key}`).prop('required')
                var fieldLabel = $(`#label-${key}`).html()
                var fieldValue = formData[key]

                $(`#${key}`).removeClass('input-error')
                if(required && (fieldValue==='' || fieldValue==='--None--')){
                    $(`#${key}`).addClass('input-error').focus()
                    return false;
                }
            }

            if(deliveredlineitem_id != ''){
                formData.action = 'edit'
            }else{
                formData.action = 'add'
            }

            formData.productid = productid
            formData.projectid = projectid
            formData.accountid = accountid
            formData.lineitem = lineitem

            $('.overlay').show();
            $.post('<?php echo site_url('Projects/saveDelivered?userid='.$this->session->userdata('userID')); ?>', formData, function(rs){
                
                if(rs.status === 'Success'){
                    var delivered_date = rs.delivered_date
                    if(formData.action == 'edit'){

                        var deliverededit = $(`.deliver-qty-${rs.deliver.lineitem_id}`).html()
                        var toatlqty = eval($('#toatl-delivery-qty').val());
                        var toatlqty = toatlqty-eval(deliverededit);

                        $(`.delivered-${rs.deliver.lineitem_id}`).html('');

                        var rowEditdelivered = `
                            <div class="row">
                                <div class="col-4" title="${rs.deliver.accountname}">${cha_length(rs.deliver.accountname,20)}</div>
                                <div class="col-4">${rs.deliver.deliver_date}</div>
                                <div class="col-2 deliver-qty-${rs.deliver.lineitem_id}">${rs.deliver.qty}</div>
                                <div class="col-2">
                                    <i class="ph-light ph-note-pencil icon-detail" onclick="$.editProductDelivered('${rs.deliver.deliver_date}','${rs.deliver.qty}','${rs.deliver.accountname}','${rs.deliver.accountid}','${rs.deliver.lineitem_id}');" role="button"></i>
                                    <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductDelivered('${rs.deliver.lineitem_id}','${rs.deliver.lineitem_id}');" role="button"></i>
                                </div>
                            </div>`
                        $(`.delivered-${rs.deliver.lineitem_id}`).html(rowEditdelivered)

                        $('#toatl-delivery-qty').val(eval(toatlqty)+eval(rs.deliver.qty));
                        var qty =eval(toatlqty)+eval(rs.deliver.qty);

                    }else{
                        var rowSavedeliver = `<div class="card-productdelivered mb-5 delivered-${rs.deliver.lineitem_id}">
                            <div class="row">
                                <div class="col-4" title="${rs.deliver.accountname}">${cha_length(rs.deliver.accountname,20)}</div>
                                <div class="col-4">${rs.deliver.deliver_date}</div>
                                <div class="col-2 deliver-qty-${rs.deliver.lineitem_id}">${rs.deliver.qty}</div>
                                <div class="col-2">
                                    <i class="ph-light ph-note-pencil icon-detail" onclick="$.editProductDelivered('${rs.deliver.deliver_date}','${rs.deliver.qty}','${rs.deliver.accountname}','${rs.deliver.accountid}','${rs.deliver.lineitem_id}');" role="button"></i>
                                    <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductDelivered('${rs.deliver.lineitem_id}','${rs.deliver.lineitem_id}');" role="button"></i>
                                </div>
                            </div>
                        </div>`
                        $(".list-productdelivered").first().prepend(rowSavedeliver);

                        var toatlqty = eval($('#toatl-delivery-qty').val());
                        $('#toatl-delivery-qty').val(eval(toatlqty)+eval(rs.deliver.qty));
                        var qty =eval(toatlqty)+eval(rs.deliver.qty);

                    }
                    
                    calculate_delivery_total(qty,lineitem,delivered_date,false)

                    $('#product_delivered_qty').val('')
                    $('#product_delivered_date').val('')
                    $('#deliveredlineitem_id').val('')
                    $('.overlay').hide();
                } else {
                    $('.overlay').hide();
                    Swal.fire('', rs.message, 'error')
                }
            },'json')        
        })

        $.getNavigationVisit = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_visit(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationVisit_More = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_visit_more(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }
        
        $.getNavigationQuotation = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_quotation(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationQuotation_More = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_quotation_more(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationDocuments = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_documents(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationDocuments_More = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_documents_more(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationPricelist = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_pricelist(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationPricelist_More = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_pricelist_more(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationSamplerequisition = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_samplerequisition(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationSamplerequisition_More = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_samplerequisition_more(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationExpense = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_expenses(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationExpense_More = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_expenses_more(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationQuestionnaire = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_questionnaire(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }

        $.getNavigationQuestionnaire_More = function(moduleSelect, crmID, type, obj, event) {
            if($(obj).hasClass("disable_click") === true) return false;
            var Page =  eval($(obj).attr('data-page')); 
            var moduleSelect = $(obj).data('moduleselect')
            
            Related_questionnaire_more(moduleSelect,crmID,type,Page)
            event.preventDefault()
        }
        $.AddRelated = function(obj) {
            //var Page =  eval($(obj).attr('data-page')); 
            var return_module = $(obj).data('return_module')
            var crmid = $(obj).data('crmid')
            var module = $(obj).data('module')
            var return_action = 'DetailView'
            var site_URL = '<?php echo $site_URL; ?>'
            window.open(`${site_URL}index.php?module=${module}&action=EditView&return_action=DetailView&parenttab=Marketing&return_module=${return_module}&related_id=${crmid}&related_module=${return_module}`, '_blank');
            //http://localhost:8090/GLAPCRM/index.php?module=Calendar&action=EditView&return_action=DetailView&parenttab=Marketing
        }

        $('.input-popup-search').keypress(function(e){
            if(e.which == 13){//Enter key pressed
                var len = $(this).val().length
                var fieldID = $(this).data('field')
                var moduleSelect = $(this).data('moduleselect')
                var filter = $(`#${fieldID}-modal-search-box`).val()
                var selectfield = $(`#${fieldID}-modal-select-box`).val()
                
                //getPopupList(moduleSelect, fieldID, filter, selectfield)
                getPopupListMulti(moduleSelect,fieldID,'','',filter, selectfield, '')
            }
        })
        $('.input-pagenumber').keypress(function(e){
            if(e.which == 13){
                var fieldID = $(this).data('field')
                var moduleSelect = $(this).data('moduleselect')

                var filter = $(`#${fieldID}-modal-search-box`).val()
                var selectfield = $(`#${fieldID}-modal-select-box`).val()
                var Page = $(`#page-num-${fieldID}`).val()
                var totalPage = $(`#page-of-${fieldID}`).html()
               
                if(Page > eval(totalPage)){
                    $(`#list-${moduleSelect}-${fieldID}`).html('')
                    return false
                }
                getPopupListMulti(moduleSelect,fieldID,'','',filter, selectfield, eval(Page))
            }
        })   
        $('.input-pagenumber').change(function(i, e){
            var fieldID = $(this).data('field')
            var moduleSelect = $(this).data('moduleselect')

            var filter = $(`#${fieldID}-modal-search-box`).val()
            var selectfield = $(`#${fieldID}-modal-select-box`).val()
            var Page = $(`#page-num-${fieldID}`).val()
            var totalPage = $(`#page-of-${fieldID}`).html()
           
            if(Page > eval(totalPage)){
                $(`#list-${moduleSelect}-${fieldID}`).html('')
                return false
            }
            getPopupListMulti(moduleSelect,fieldID,'','',filter, selectfield, eval(Page))
        })
        
    })

</script>