<?php $hide_blocks = array('Owner/Developer Information 1','Owner/Developer Information 2','Owner/Developer Information 3','Owner/Developer Information 4','Consultant Information','Construction Information 1','Construction Information 2','Architecture Information 1','Architecture Information 2','Designer Information 1','Designer Information 2','Designer Information 3','Designer Information 4','Contractor Information 1','Contractor Information 2','Contractor Information 3','Contractor Information 4','Contractor Information 5','Contractor Information 6','Contractor Information 7','Contractor Information 8','Contractor Information 9','Contractor Information 10','Sub Contractor Information 1','Sub Contractor Information 2','Sub Contractor Information 3','Comment Information'); ?>

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

<div class="page-content">
  <div class="row">
    
    <div class="col-12 col-sm-9">
      <div data-spy="scroll" data-target="#verticalScrollspy">

        <form id="form-projects" method="post" action="javascript:void(0)">
        <?php if(is_array($blocks)){ foreach($blocks as $index => $block) { ?>
            
            <?php if(!in_array($block['header_name'], $hide_blocks)){ ?>
                <div class="card-box mb-10" id="<?php echo "block_name_".$index; ?>" >
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
                                $field['configmodule'] = $configmodule;
                                if($field['columnname'] == 'dealid'){
                                    $field['value'] = $dealId;
                                    $field['value_name'] = $dealNo;
                                }
                                ?>
                                <?php echo inputGroup_Web($field); ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if($block['header_name'] == 'Key Man Customer Information'){ ?>
                    <!-- Product Information -->
                    <div class="card-box mb-10" id="block_name_product" >
                        <div class="card-box-header flex">
                            <div class="card-box-title flex-1">
                                Product Information
                            </div>
                            <div class="card-box-action flex-none">
                                <div data-bs-toggle="collapse" href="#boxproduct" role="button" aria-expanded="false">
                                    <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                                </div>
                            </div>
                        </div>
                        <div class="collapse show" id="boxproduct">
                            <div class="card-box-body">
                                <div class="mb-5">

                                    <div class="table-product" style="overflow-x:auto;">
                                        <table id="proTab" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px">
                                            <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                                <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                                <td class="pd-10 w-400px">Product items</td>
                                                <td class="pd-10 w-300px">Brand</td>
                                                <td class="pd-10 w-300px">Product Group</td>
                                                <td class="pd-10 w-300px">Dealer</td>
                                                <td class="pd-10 w-300px">First Delivered</td>
                                                <td class="pd-10 w-300px">Last Delivered</td>
                                                <td class="pd-10 w-100px">Est</td>
                                                <td class="pd-10 w-300px border-bottom-radius-5">Sell Price</td>
                                            </tr>
                                            
                                            <tr id="row1" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <input type="hidden" id="deleted1" name="deleted1" value="0">
                                                </td>
                                                    
                                                <td>

                                                    <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'productid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Products','modal' => 'productid1','fieldName'=> 'input-productid1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'productinventory'] ); ?>
                                                    <div class="mb-2">
                                                        <textarea class="base-input " id="descriptions1" name="descriptions1" rows="2"></textarea>
                                                    </div>

                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="product_brand1" name="product_brand1" readonly value="">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="product_group1" name="product_group1" readonly value="">
                                                </td>
                                                <td>
                                                    <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'dealerid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'dealerid1','fieldName'=> 'input-dealerid1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'productinventory'] ); ?>
                                                </td>

                                                <td>
                                                   <div class="base-input-group">
                                                        <input type="text" class="base-input-text" id="first_delivered_date1" value="" name="first_delivered_date1" readonly placeholder="DD/MM/YYYY">
                                                        <div class="base-input-group-action">
                                                            <i class="ph-calendar-blank cursor-pointer" for="first_delivered_date1"></i>
                                                        </div>
                                                    </div> 
                                                </td>
                                                <td>
                                                    <div class="base-input-group">
                                                        <input type="text" class="base-input-text " id="last_delivered_date1" value="" name="last_delivered_date1" readonly placeholder="DD/MM/YYYY">
                                                        <div class="base-input-group-action">
                                                            <i class="ph-calendar-blank cursor-pointer" for="last_delivered_date1"></i>
                                                        </div>
                                                    </div> 
                                                </td>
                                                <td>
                                                    <span id="label-estimated1" style="display: none;">Est</span>
                                                    <input type="text" class="base-input" id="estimated1" name="estimated1" value="" onkeyup="calcTotal();" onkeypress=" return isNumberPricelist(event);" >
                                                </td>
                                                
                                                <td>
                                                    <input type="text" class="base-input " id="listPrice1" name="listPrice1" value="" onkeypress=" return isNumberPricelist(event);">
                                                </td>
                                            </tr>
                                            
                                        </table>

                                        <table class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px">
                                            <tr class="font-14 font-bold" style="background-color: #F8FAFC !important;">
                                                <td class="pd-10 w-150px border-top-radius-5"></td>
                                                <td class="pd-10 w-400px"></td>
                                                <td class="pd-10 w-300px"></td>
                                                <td class="pd-10 w-300px"></td>
                                                <td class="pd-10 w-300px"></td>
                                                <td class="pd-10 w-300px"></td>
                                                <td class="pd-10 w-300px v-align-middle" align="right">
                                                    <span class="font-16 font-bold">Total</span></td>
                                                <td class="pd-10 w-100px">
                                                    <input type="text" class="base-input base-input-text" name="total_est" id="total_est" value="" readonly>
                                                </td>
                                                <td class="pd-10 w-300px border-bottom-radius-5"></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <input type="hidden" name="num_products" id="num_products" value="1">

                                    <button type="button" class="btn_add_product" id="add_product">
                                        <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add Product
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Information -->

                    <!-- Competitor Product Information -->
                    <div class="card-box mb-10" id="block_name_competitor" >
                        <div class="card-box-header flex">
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
                                            
                                            <tr id="row_com1" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <input type="hidden" id="deleted_com1" name="deleted_com1" value="0">
                                                </td>
                                                    
                                                <td>
                                                    <?php echo inputPopupMulti(['uitype' => '', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'competitorproductid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Competitorproduct','modal' => 'competitorproductid1','fieldName'=> 'input-competitorproductid1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'competitorinventory'] ); ?>
                                                    <div class="mb-2">
                                                        <textarea class="base-input " id="descriptions_com1" name="descriptions_com1" rows="2"></textarea>
                                                    </div>

                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="competitor_brand1" name="competitor_brand1" readonly value="">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="comprtitor_product_group1" name="comprtitor_product_group1" readonly value="">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="comprtitor_product_size1" name="comprtitor_product_size1" readonly value="">
                                                </td>

                                                <td>
                                                   <input type="text" class="base-input base-input-text" id="comprtitor_product_thickness1" name="comprtitor_product_thickness1" readonly value="">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input" id="comprtitor_estimated_unit1" name="comprtitor_estimated_unit1" onkeypress=" return isNumberPricelist(event);" value="">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input" id="competitor_price1" name="competitor_price1" readonly value="">
                                                </td>

                                            </tr>
                                            
                                        </table>

                                    </div>

                                    <input type="hidden" name="num_compeitor" id="num_compeitor" value="1">

                                    <button type="button" class="btn_add_compeitor" id="add_compeitor">
                                        <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add Compeitor Product
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Competitor Product Information -->
                <?php } ?>

            <?php }else if($block['header_name'] == 'Owner/Developer Information 1'){ ?>
                <div class="card-box mb-10" id="block_name_customer" >
                    <div class="card-box-header flex">
                        <div class="card-box-title flex-1">
                            Customer Information
                        </div>
                        <div class="card-box-action flex-none">
                            <div data-bs-toggle="collapse" href="#box<?php echo $index; ?>" role="button" aria-expanded="false">
                                <i class="ph-caret-up-fill" onclick="$(this).toggleClass('ph-caret-up-fill ph-caret-down-fill')"></i>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show" id="box<?php echo $index; ?>">
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
                                            <td class="pd-10 ">ชื่อผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 ">หมายเลขผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ออกแบบโครงการ (ไทย)</td>
                                            <td class="pd-10 ">ชื่อผู้ออกแบบโครงการ (EN)</td>
                                            <td class="pd-10 ">กลุ่มผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 ">ประเภทธุรกิจผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 ">ระดับผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ติดต่อผู้ออกแบบโครงการ</td>
                                            <td class="pd-10 ">Service Level</td>
                                            <td class="pd-10 ">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5">% Com. Sales</td>
                                        </tr>
                                        
                                        <tr id="row_designer1" class="row_data">
                                            <td class="txt-center txt-middle">
                                                <input type="hidden" id="deleted_designer1" name="deleted_designer1" value="0">
                                            </td>
                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'designer1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'designer1','fieldName'=> 'input-designer1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'designerinventory'] ); ?>
                                            </td>

                                            <td><input type="text" class="base-input base-input-text" id="designer_no1" name="designer_no1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="designer_name_th1" name="designer_name_th1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="designer_name_en1" name="designer_name_en1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="designer_group1" name="designer_group1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="designer_industry1" name="designer_industry1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="designer_grade1" name="designer_grade1" readonly value=""></td>

                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_designer1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_designer1','fieldName'=> 'input-contact_designer1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'designerinventory'] ); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="service_level_designer1" name="service_level_designer1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="sales_designer_name1" name="sales_designer_name1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input percent_com" id="percen_com_sales_designer1" name="percen_com_sales_designer1" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <input type="hidden" name="num_designer" id="num_designer" value="1">
                                <button type="button" class="btn_add_designer" id="add_designer">
                                    <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Designer
                                </button>
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
                                            <td class="pd-10 ">ชื่อสถาปนิกโครงการ</td>
                                            <td class="pd-10 ">หมายเลขสถาปนิกโครงการ</td>
                                            <td class="pd-10 ">ชื่อสถาปนิกโครงการ (ไทย)</td>
                                            <td class="pd-10 ">ชื่อสถาปนิกโครงการ (EN)</td>
                                            <td class="pd-10 ">กลุ่มสถาปนิกโครงการ</td>
                                            <td class="pd-10 ">ประเภทธุรกิจสถาปนิกโครงการ</td>
                                            <td class="pd-10 ">ระดับสถาปนิกโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ติดต่อสถาปนิกโครงการ</td>
                                            <td class="pd-10 ">Service Level</td>
                                            <td class="pd-10 ">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5">% Com. Sales</td>
                                        </tr>
                                        
                                        <tr id="row_arc1" class="row_data">
                                            <td class="txt-center txt-middle">
                                                <input type="hidden" id="deleted_architecture1" name="deleted_architecture1" value="0">
                                            </td>
                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'architecture1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'architecture1','fieldName'=> 'input-architecture1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'architectureinventory'] ); ?>
                                            </td>

                                            <td><input type="text" class="base-input base-input-text" id="architecture_no1" name="architecture_no1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="architecture_name_th1" name="architecture_name_th1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="architecture_name_en1" name="architecture_name_en1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="architecture_group1" name="architecture_group1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="architecture_industry1" name="architecture_industry1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="architecture_grade1" name="architecture_grade1" readonly value=""></td>

                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_architecture1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_architecture1','fieldName'=> 'input-contact_architecture1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'architectureinventory'] ); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="service_level_architecture1" name="service_level_architecture1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="sales_architecture_name1" name="sales_architecture_name1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input percent_com" id="percen_com_sales_architecture1" name="percen_com_sales_architecture1" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <input type="hidden" name="num_architecture" id="num_architecture" value="1">
                                <button type="button" class="btn_add_architecture" id="add_architecture">
                                    <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Architecture
                                </button>
                            </div>
                            <!-- Architecture -->

                            <!-- Owner/Developer -->
                            <div class="mb-5 bg-custom">
                                <div class="mb-10 mt-10">
                                    <label class="pl-5 mb-5"><span>Owner/Developer</span> </label>
                                </div>
                                <div class="table-product" style="overflow-x:auto;">
                                    <table id="proTabOwner" class="table table-striped table-bordered table-sm" cellspacing="0" style="width: 2000px">
                                        <tr class="font-14 font-bold" style="background-color: #F1F5F9 !important;">
                                            <td class="pd-10 w-150px border-top-radius-5" align="center">No.</td>
                                            <td class="pd-10 ">ชื่อเจ้าของโครงการ</td>
                                            <td class="pd-10 ">หมายเลขเจ้าของโครงการ</td>
                                            <td class="pd-10 ">ชื่อเจ้าของโครงการ (ไทย)</td>
                                            <td class="pd-10 ">ชื่อเจ้าของโครงการ (EN)</td>
                                            <td class="pd-10 ">กลุ่มเจ้าของโครงการ</td>
                                            <td class="pd-10 ">ประเภทธุรกิจเจ้าของโครงการ</td>
                                            <td class="pd-10 ">ระดับเจ้าของโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ติดต่อเจ้าของโครงการ</td>
                                            <td class="pd-10 ">Service Level</td>
                                            <td class="pd-10 ">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5">% Com. Sales</td>
                                        </tr>
                                        
                                        <tr id="row_owner1" class="row_data">
                                            <td class="txt-center txt-middle">
                                                <input type="hidden" id="deleted_owner1" name="deleted_owner1" value="0">
                                            </td>
                                                
                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'owner1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'owner1','fieldName'=> 'input-owner1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'ownerinventory'] ); ?>
                                            </td>
                                           
                                            <td><input type="text" class="base-input base-input-text" id="owner_no1" name="owner_no1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="owner_name_th1" name="owner_name_th1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="owner_name_en1" name="owner_name_en1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="owner_group1" name="owner_group1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="owner_industry1" name="owner_industry1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="owner_grade1" name="owner_grade1" readonly value=""></td>
                                             <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_owner1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_owner1','fieldName'=> 'input-contact_owner1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'ownerinventory'] ); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="service_level_owner1" name="service_level_owner1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="sales_owner_name1" name="sales_owner_name1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input percent_com" id="percen_com_sales_owner1" name="percen_com_sales_owner1" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <input type="hidden" name="num_owner" id="num_owner" value="1">
                                <button type="button" class="btn_add_owner" id="add_owner">
                                    <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Owner/Developer
                                </button>
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
                                            <td class="pd-10 ">ชื่อที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 ">หมายเลขที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 ">ชื่อที่ปรึกษาโครงการ (ไทย)</td>
                                            <td class="pd-10 ">ชื่อที่ปรึกษาโครงการ (EN)</td>
                                            <td class="pd-10 ">กลุ่มที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 ">ประเภทธุรกิจที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 ">ระดับที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ติดต่อที่ปรึกษาโครงการ</td>
                                            <td class="pd-10 ">Service Level</td>
                                            <td class="pd-10 ">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5">% Com. Sales</td>
                                        </tr>
                                        
                                        <tr id="row_consul1" class="row_data">
                                            <td class="txt-center txt-middle">
                                                <input type="hidden" id="deleted_consultant1" name="deleted_consultant1" value="0">
                                            </td>
                                                
                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'consultant1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'consultant1','fieldName'=> 'input-consultant1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'consultantinventory'] ); ?>
                                            </td>

                                            <td><input type="text" class="base-input base-input-text" id="consultant_no1" name="consultant_no1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="consultant_name_th1" name="consultant_name_th1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="consultant_name_en1" name="consultant_name_en1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="consultant_group1" name="consultant_group1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="consultant_industry1" name="consultant_industry1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="consultant_grade1" name="consultant_grade1" readonly value=""></td>

                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_consultant1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_consultant1','fieldName'=> 'input-contact_consultant1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'consultantinventory'] ); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="service_level_consultant1" name="service_level_consultant1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="sales_consultant_name1" name="sales_consultant_name1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input percent_com" id="percen_com_sales_consultant1" name="percen_com_sales_consultant1" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <input type="hidden" name="num_consultant" id="num_consultant" value="1">
                                <button type="button" class="btn_add_consultant" id="add_consultant">
                                    <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Consultant
                                </button>
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
                                            <td class="pd-10 ">ชื่อช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 ">หมายเลขช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 ">ชื่อช่างก่อสร้างโครงการ (ไทย)</td>
                                            <td class="pd-10 ">ชื่อช่างก่อสร้างโครงการ (EN)</td>
                                            <td class="pd-10 ">กลุ่มช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 ">ประเภทธุรกิจช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 ">ระดับช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ติดต่อช่างก่อสร้างโครงการ</td>
                                            <td class="pd-10 ">Service Level</td>
                                            <td class="pd-10 ">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5">% Com. Sales</td>
                                        </tr>
                                        
                                        <tr id="row_const1" class="row_data">
                                            <td class="txt-center txt-middle">
                                                <input type="hidden" id="deleted_const1" name="deleted_const1" value="0">
                                            </td>
                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'construction1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'construction1','fieldName'=> 'input-construction1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'constructioninventory'] ); ?>
                                            </td>

                                            <td><input type="text" class="base-input base-input-text" id="construction_no1" name="construction_no1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="construction_name_th1" name="construction_name_th1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="construction_name_en1" name="construction_name_en1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="construction_group1" name="construction_group1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="construction_industry1" name="construction_industry1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="construction_grade1" name="construction_grade1" readonly value=""></td>

                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_construction1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_construction1','fieldName'=> 'input-contact_construction1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'constructioninventory'] ); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="service_level_construction1" name="service_level_construction1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="sales_construction_name1" name="sales_construction_name1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input percent_com" id="percen_com_sales_construction1" name="percen_com_sales_construction1" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <input type="hidden" name="num_construction" id="num_construction" value="1">
                                <button type="button" class="btn_add_construction" id="add_construction">
                                    <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Construction
                                </button>
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
                                            <td class="pd-10 ">ชื่อผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 ">หมายเลขผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้รับเหมาโครงการ (ไทย)</td>
                                            <td class="pd-10 ">ชื่อผู้รับเหมาโครงการ (EN)</td>
                                            <td class="pd-10 ">กลุ่มผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 ">ประเภทธุรกิจผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 ">ระดับผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 ">ชื่อผู้ติดต่อผู้รับเหมาโครงการ</td>
                                            <td class="pd-10 ">Service Level</td>
                                            <td class="pd-10 ">Sales Owner Name</td>
                                            <td class="pd-10 border-bottom-radius-5">% Com. Sales</td>
                                        </tr>
                                        
                                        <tr id="row_contractor1" class="row_data">
                                            <td class="txt-center txt-middle">
                                                <input type="hidden" id="deleted_contractor1" name="deleted_contractor1" value="0">
                                            </td>
                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'contractor1','fieldName'=> 'input-contractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'contractorinventory'] ); ?>
                                            </td>
                                            
                                            <td><input type="text" class="base-input base-input-text" id="contractor_no1" name="contractor_no1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="contractor_name_th1" name="contractor_name_th1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="contractor_name_en1" name="contractor_name_en1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="contractor_group1" name="contractor_group1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="contractor_industry1" name="contractor_industry1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="contractor_grade1" name="contractor_grade1" readonly value=""></td>

                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_contractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_contractor1','fieldName'=> 'input-contact_contractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'contractorinventory'] ); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="service_level_contractor1" name="service_level_contractor1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="sales_contractor_name1" name="sales_contractor_name1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input percent_com" id="percen_com_sales_contractor1" name="percen_com_sales_contractor1" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <input type="hidden" name="num_contractor" id="num_contractor" value="1">
                                <button type="button" class="btn_add_contractor" id="add_contractor">
                                    <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Contractor
                                </button>
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
                                        
                                        <tr id="row_subcontractor1" class="row_data">
                                            <td class="txt-center txt-middle">
                                                <input type="hidden" id="deleted_subcontractor1" name="deleted_subcontractor1" value="0">
                                            </td>
                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'subcontractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'subcontractor1','fieldName'=> 'input-subcontractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'subcontractorinventory'] ); ?>
                                            </td>
                                            
                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_no1" name="sub_contractor_no1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_th1" name="sub_contractor_name_th1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_en1" name="sub_contractor_name_en1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_group1" name="sub_contractor_group1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_industry1" name="sub_contractor_industry1" readonly value=""></td>
                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_grade1" name="sub_contractor_grade1" readonly value=""></td>

                                            <td>
                                                <?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_subcontractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_subcontractor1','fieldName'=> 'input-contact_subcontractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'subcontractorinventory'] ); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="service_level_sub_contractor1" name="service_level_sub_contractor1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input base-input-text" id="sales_sub_contractor_name1" name="sales_sub_contractor_name1" readonly value="">
                                            </td>
                                            <td>
                                                <input type="text" class="base-input percent_com" id="percen_com_sales_sub_contractor1" name="percen_com_sales_sub_contractor1" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">
                                            </td>
                                        </tr>
                                        
                                    </table>

                                </div>

                                <input type="hidden" name="num_subcontractor" id="num_subcontractor" value="1">
                                <button type="button" class="btn_add_subcontractor" id="add_subcontractor">
                                    <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Landscape
                                </button>
                            </div>
                            <!-- Landscape -->

                        </div>
                    </div>
                </div>

            <?php } ?>

        <?php }} ?>
        </form>
      </div>
    </div>

    <nav class="col-3 col-sm-3" id="verticalScrollspy">
        
        <ul class="nav flex-column nav-pills">
            <li class="nav-item">
                <button type="button" class="btn btn-primary btn-save-form" onclick="$.submitForm()"><i class="ph-bold ph-floppy-disk" style="font-size: 18px;vertical-align: inherit;"></i>&nbsp;Save</button>
                
                <!-- <button type="button" class="btn btn-default btn-cancel-form" onclick="$.CancelForm()"><i class="ph-bold ph-x-circle" style="font-size: 18px;vertical-align: inherit;"></i>&nbsp;Cancel</button> -->
            </li>
            
        <?php if(is_array($blocks)){ foreach($blocks as $index => $block) { ?>
            
            <?php if(!in_array($block['header_name'], $hide_blocks)){ ?>
                <li class="nav-item">
                    <a class="nav-link list-item-bar <?php if($index==0){ echo 'active'; } ?>" href="#<?php echo "block_name_".$index; ?>"><?php echo $block['header_name']; ?></a>
                </li>
                <?php if($block['header_name'] == 'Key Man Customer Information'){ ?>
                    <li class="nav-item">
                        <a class="nav-link list-item-bar" href="#block_name_product" >Product Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link list-item-bar" href="#block_name_competitor" >Competitor Product Information</a>
                    </li>
                <?php } ?>
            <?php }else if($block['header_name'] == 'Owner/Developer Information 1'){ ?>
                <li class="nav-item">
                    <a class="nav-link list-item-bar" href="#block_name_customer" >Customer Information</a>
                </li>

            <?php } ?>

        <?php }} ?>
        </ul>
    </nav>

  </div>
</div>

<!-- End Page Content -->
<style type="text/css">
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
    .btn-cancel-form{
        margin-left: 10px;
        width: auto;
        margin-bottom: 10px;
        border-radius: 10px;
        vertical-align:sub;
        box-shadow: 0 0 5px #d1cfcf;
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
</style>

<script>
    var offSet = 0;
    function getPopupList(moduleSelect, fieldID, filter, selectfield, Page, relate_field_up, relate_field_down){

        var params = {moduleSelect, offSet}
        var field_down = ''
        var field_up = ''
        if(filter !== undefined) params.filter = filter
        if(selectfield !== undefined) params.selectfield = selectfield
        if(Page !== undefined) params.offSet = eval((Page*20)-20)
        
        if(relate_field_up !== undefined && relate_field_up !== ''){
            params.relate_field_up = $(`#${relate_field_up}`).val()
            field_up = relate_field_up
        }
        
        if(relate_field_down !== undefined && relate_field_down !== ''){
            var field_down = relate_field_down
        }

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
            const selectedField = configmodule[moduleSelect].header
            var headItem = ''; 
            selectedField.map((item, i) => {
                var customBorder = ''
                if(i === 0) customBorder = 'bd-left bd-radius-l'
                if(i === (selectedField.length -1)) customBorder = 'bd-right bd-radius-r'
                headItem += `<div class="col-3 p-10 bd-top bd-bottom ${customBorder}"><label class="font-14 font-bold">${item.label}</div>`
            })
            $(`#header-${fieldID}`).append(headItem)
            
            rs['row'].map(item => {
                var rowItem = $('<div />',{ class:` flex width-full bg-F8 px-15 py-5 mb-5 border-r10 row-h` })
                var rowHtml = `<div class="col-3 p-5"><label class="font-14" title="${item[selectedField[0].field]}">${cha_length(item[selectedField[0].field],20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.name}">${cha_length(item[selectedField[1].field],20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.record1}">${cha_length(item[selectedField[2]?.field] ?? '',20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.record2}">${cha_length(item[selectedField[3]?.field] ?? '',20)}</label></div>`

                $(rowItem).html(rowHtml)
                $(rowItem).click(function(){
                    $.setPopupValue_Web(fieldID, item, field_up, field_down)
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
                    //$(`#next-${fieldID}`).removeClass("disable_click");
                    //$(`#end-${fieldID}`).removeClass("disable_click");
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

        if(moduleSelect === 'Contacts'){
            var accountField = fieldID.replace('contact_', '')
            var accountID = $(`#${accountField}`).val()
            params = {
                ...params,
                ...{ relate_field_up: accountID }
            }
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
            const selectedField = configmodule[moduleSelect].header
            var headItem = ''; 
            selectedField.map((item, i) => {
                var customBorder = ''
                if(i === 0) customBorder = 'bd-left bd-radius-l'
                if(i === (selectedField.length -1)) customBorder = 'bd-right bd-radius-r'
                headItem += `<div class="col-3 p-10 bd-top bd-bottom ${customBorder}"><label class="font-14 font-bold">${item.label}</div>`
            })
            $(`#header-${fieldID}`).append(headItem)
            
            rs['row'].map(item => {
                var rowItem = $('<div />',{ class:` flex width-full bg-F8 px-15 py-5 mb-5 border-r10 row-h` })
                var rowHtml = `<div class="col-3 p-5"><label class="font-14" title="${item[selectedField[0].field]}">${cha_length(item[selectedField[0].field],20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.name}">${cha_length(item[selectedField[1].field],20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.record1}">${cha_length(item[selectedField[2]?.field] ?? '',20)}</label></div>
                               <div class="col-3 p-5"><label class="font-14" title="${item.record2}">${cha_length(item[selectedField[3]?.field] ?? '',20)}</label></div>`


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
                    //$(`#next-${fieldID}`).removeClass("disable_click");
                    //$(`#end-${fieldID}`).removeClass("disable_click");
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
    
    function trash_owner(block_name){
        $("#"+block_name).remove();
        var number = $(".block_owner").length;
        number=number-1
        if(number < 4){$('.btn_add_customer').css('display','block')}
    }

    function trash_architecture(block_name){
        $("#"+block_name).remove();
        var number = $(".block_architecture").length;
        number=number-1
        if(number < 2){$('.btn_add_architecture').css('display','block')}
    }
    
    function trash_construction(block_name){
        $("#"+block_name).remove();
        var number = $(".block_construction").length;
        number=number-1
        if(number < 2){$('.btn_add_construction').css('display','block')}
    }
    
    function trash_designer(block_name){
        $("#"+block_name).remove();
        var number = $(".block_designer").length;
        number=number-1
        if(number < 4){$('.btn_add_designer').css('display','block')}
    }
    
    

    function trash_contractor(block_name){
        $("#"+block_name).remove();
        var number = $(".block_contractor").length;
        number=number-1
        if(number < 10){$('.btn_add_contractor').css('display','block')}
    }

    function trash_subcontractor(block_name){
        $("#"+block_name).remove();
        var number = $(".block_subcontractor").length;
        number=number-1
        if(number < 3){$('.btn_add_subcontractor').css('display','block')}
    }
    
    function PrdUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-productid','productid','descriptions','product_brand','product_group','input-dealerid','dealerid','first_delivered_date','last_delivered_date','estimated','plan','delivered','remain_on_hand','listPrice');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTab');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row"+iCount))
                {
                    if(document.getElementById("row"+iCount).style.display != 'none' && document.getElementById('deleted'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none' && document.getElementById('deleted'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                TTemp = document.getElementById(sId).innerHTML;
                document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                document.getElementById(sSwapId).innerHTML = TTemp;

            }
        } 
    }

    function PrdRemove(i){
        var tableName = document.getElementById('proTab');
        var prev = tableName.rows.length;
        document.getElementById("row"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted1" name="deleted1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted${iPrevCount}" name="deleted${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="PrdRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="PrdUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("productid"+i).value = "";
        document.getElementById('deleted'+i).value = 1;

        calcTotal()

    }

    function settotalrowsPro() {
        var max_row_count = document.getElementById('proTab').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_products').val(max_row_count);
    }

    function calcTotal() {
        var max_row_count = document.getElementById('proTab').rows.length;
        max_row_count = eval(max_row_count)-1;
        var est = 0.0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted'+rowId) && document.getElementById('deleted'+rowId).value == 0)
            {   
                var estimated = $("#estimated"+rowId).val();
                
                if(estimated === '') estimated = 0                
                //console.log(estimated);
                est = eval(est) + eval(estimated);
            }
        }
        if(isNaN(est)) {
            var est = 0;
        }
        $('#total_est').val(est);

        if(est >=0 && est <=99){
            $("#project_size").val("S: 0-99 sh.");
        }else if(est >=100 && est <=199){
            $("#project_size").val("M: 100-199 sh.");
        }else if(est >=200 && est <=499){
            $("#project_size").val("L: 200-499 sh.");
        }else if(est >=500 && est <=999){
            $("#project_size").val("XL: 500-999 sh.");
        }else if(est >=1000 && est <=9999){
            $("#project_size").val("2XL: 1000-9999 sh.");
        }else if(est >=10000){
            $("#project_size").val("3XL : >=10,000");
        }
    }

    function calc_owner() {
        var max_row_count = document.getElementById('proTabOwner').rows.length;
        max_row_count = eval(max_row_count)-1;
        var owner = 0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted_owner'+rowId) && document.getElementById('deleted_owner'+rowId).value == 0)
            {   
                var sales_owner = $("#percen_com_sales_owner"+rowId).val();
            
                if(sales_owner === '') sales_owner = 0
                owner = eval(owner) + eval(sales_owner)
                
                if(owner>100){
                   $("#percen_com_sales_owner"+rowId).val('')
                }   
            }   
        }
    }

    function calc_consultant() {
        var max_row_count = document.getElementById('proTabConsultant').rows.length;
        max_row_count = eval(max_row_count)-1;
        var consultant = 0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted_consultant'+rowId) && document.getElementById('deleted_consultant'+rowId).value == 0)
            {   
                var sales_consultant = $("#percen_com_sales_consultant"+rowId).val();
            
                if(sales_consultant === '') sales_consultant = 0
                consultant = eval(consultant) + eval(sales_consultant)
                
                if(consultant>100){
                   $("#percen_com_sales_consultant"+rowId).val('')
                }   
            }   
        }
    }

    function calc_architecture() {
        var max_row_count = document.getElementById('proTabArchitec').rows.length;
        max_row_count = eval(max_row_count)-1;
        var architecture = 0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted_architecture'+rowId) && document.getElementById('deleted_architecture'+rowId).value == 0)
            {   
                var sales_architecture = $("#percen_com_sales_architecture"+rowId).val();
            
                if(sales_architecture === '') sales_architecture = 0
                architecture = eval(architecture) + eval(sales_architecture)
                
                if(architecture>100){
                   $("#percen_com_sales_architecture"+rowId).val('')
                }   
            }   
        }
    }

    function calc_construction() {
        var max_row_count = document.getElementById('proTabConstruction').rows.length;
        max_row_count = eval(max_row_count)-1;
        var consultant = 0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted_const'+rowId) && document.getElementById('deleted_const'+rowId).value == 0)
            {   
                var sales_consultant = $("#percen_com_sales_construction"+rowId).val();
            
                if(sales_consultant === '') sales_consultant = 0
                consultant = eval(consultant) + eval(sales_consultant)
                
                if(consultant>100){
                   $("#percen_com_sales_construction"+rowId).val('')
                }   
            }   
        }
    }

    function calc_designer() {
        var max_row_count = document.getElementById('proTabDesigner').rows.length;
        max_row_count = eval(max_row_count)-1;
        var designer = 0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted_designer'+rowId) && document.getElementById('deleted_designer'+rowId).value == 0)
            {   
                var sales_designer = $("#percen_com_sales_designer"+rowId).val();
            
                if(sales_designer === '') sales_designer = 0
                designer = eval(designer) + eval(sales_designer)
                
                if(designer>100){
                   $("#percen_com_sales_designer"+rowId).val('')
                }   
            }   
        }
    }

    function calc_contractor() {
        var max_row_count = document.getElementById('proTabContractor').rows.length;
        max_row_count = eval(max_row_count)-1;
        var contractor = 0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted_contractor'+rowId) && document.getElementById('deleted_contractor'+rowId).value == 0)
            {   
                var sales_contractor = $("#percen_com_sales_contractor"+rowId).val();
            
                if(sales_contractor === '') sales_contractor = 0
                contractor = eval(contractor) + eval(sales_contractor)
                
                if(contractor>100){
                   $("#percen_com_sales_contractor"+rowId).val('')
                }   
            }   
        }
    }

    function calc_sub_contractor() {
        var max_row_count = document.getElementById('proTabSubContractor').rows.length;
        max_row_count = eval(max_row_count)-1;
        var subcontractor = 0;
        for(var i=1;i<=max_row_count;i++)
        {
            rowId = i;
            if(document.getElementById('deleted_subcontractor'+rowId) && document.getElementById('deleted_subcontractor'+rowId).value == 0)
            {   
                var sales_subcontractor = $("#percen_com_sales_sub_contractor"+rowId).val();
            
                if(sales_subcontractor === '') sales_subcontractor = 0
                subcontractor = eval(subcontractor) + eval(sales_subcontractor)
                
                if(subcontractor>100){
                   $("#percen_com_sales_sub_contractor"+rowId).val('')
                }   
            }   
        }
    }

    function ComUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-competitorproductid','competitorproductid','descriptions_com','competitor_brand','comprtitor_product_group','comprtitor_product_size','comprtitor_product_thickness','comprtitor_estimated_unit','competitor_price');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabCom');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_com"+iCount))
                {
                    if(document.getElementById("row_com"+iCount).style.display != 'none' && document.getElementById('deleted_com'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_com"+iCount) && document.getElementById("row_com"+iCount).style.display != 'none' && document.getElementById('deleted_com'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                TTemp = document.getElementById(sId).innerHTML;
                document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                document.getElementById(sSwapId).innerHTML = TTemp;

            }
        } 
    }

    function ComRemove(i){
        var tableName = document.getElementById('proTabCom');
        var prev = tableName.rows.length;
        document.getElementById("row_com"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_com"+iCount) && document.getElementById("row_com"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_com"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_com"+iCount) && document.getElementById("row_com"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_com"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_com1" name="deleted_com1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_com"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_com${iPrevCount}" name="deleted_com${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ComRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ComUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("competitorproductid"+i).value = "";
        document.getElementById('deleted_com'+i).value = 1;
    }

    function settotalrowsCom() {
        var max_row_count = document.getElementById('proTabCom').rows.length;
            max_row_count = eval(max_row_count)-1;
        //set the total number of products
        $('#num_compeitor').val(max_row_count);
    }

    function OwnerUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-owner','owner','input-contact_owner','contact_owner','service_level_owner','sales_owner_name','percen_com_sales_owner','owner_no','owner_name_th','owner_name_en','owner_group','owner_industry','owner_grade');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabOwner');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_owner"+iCount))
                {
                    if(document.getElementById("row_owner"+iCount).style.display != 'none' && document.getElementById('deleted_owner'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_owner"+iCount) && document.getElementById("row_owner"+iCount).style.display != 'none' && document.getElementById('deleted_owner'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                TTemp = document.getElementById(sId).innerHTML;
                document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                document.getElementById(sSwapId).innerHTML = TTemp;

            }
        } 
    }

    function OwnerRemove(i){
        var tableName = document.getElementById('proTabOwner');
        var prev = tableName.rows.length;
        document.getElementById("row_owner"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_owner"+iCount) && document.getElementById("row_owner"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_owner"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_owner"+iCount) && document.getElementById("row_owner"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_owner"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_owner1" name="deleted_owner1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_owner"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_owner${iPrevCount}" name="deleted_owner${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="OwnerRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="OwnerUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("owner"+i).value = "";
        document.getElementById('deleted_owner'+i).value = 1;
    }

    function settotalrowsOwner() {
        var max_row_count = document.getElementById('proTabOwner').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_owner').val(max_row_count);
    }

    function ConsultantUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-consultant','consultant','input-contact_consultant','contact_consultant','service_level_consultant','sales_consultant_name','percen_com_sales_consultant','consultant_no','consultant_name_th','consultant_name_en','consultant_group','consultant_industry','consultant_grade');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabConsultant');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_consul"+iCount))
                {
                    if(document.getElementById("row_consul"+iCount).style.display != 'none' && document.getElementById('deleted_consultant'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_consul"+iCount) && document.getElementById("row_consul"+iCount).style.display != 'none' && document.getElementById('deleted_consultant'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                TTemp = document.getElementById(sId).innerHTML;
                document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                document.getElementById(sSwapId).innerHTML = TTemp;

            }
        } 
    }

    function ConsultantRemove(i){
        var tableName = document.getElementById('proTabConsultant');
        var prev = tableName.rows.length;
        document.getElementById("row_consul"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_consul"+iCount) && document.getElementById("row_consul"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_consul"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_consul"+iCount) && document.getElementById("row_consul"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_consul"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_consultant1" name="deleted_consultant1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_consul"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_consultant${iPrevCount}" name="deleted_consultant${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ConsultantRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ConsultantUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("consultant"+i).value = "";
        document.getElementById('deleted_consultant'+i).value = 1;
    }

    function settotalrowsConsultant() {
        var max_row_count = document.getElementById('proTabConsultant').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_consultant').val(max_row_count);
    }

    function ArchitectureUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-architecture','architecture','input-contact_architecture','contact_architecture','service_level_architecture','sales_architecture_name','percen_com_sales_architecture','architecture_no','architecture_name_th','architecture_name_en','architecture_group','architecture_industry','architecture_grade');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabArchitec');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_arc"+iCount))
                {
                    if(document.getElementById("row_arc"+iCount).style.display != 'none' && document.getElementById('deleted_architecture'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_arc"+iCount) && document.getElementById("row_arc"+iCount).style.display != 'none' && document.getElementById('deleted_architecture'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                TTemp = document.getElementById(sId).innerHTML;
                document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                document.getElementById(sSwapId).innerHTML = TTemp;

            }
        } 
    }

    function ArchitectureRemove(i){
        var tableName = document.getElementById('proTabArchitec');
        var prev = tableName.rows.length;
        document.getElementById("row_arc"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_arc"+iCount) && document.getElementById("row_arc"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_arc"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_arc"+iCount) && document.getElementById("row_arc"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_arc"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_architecture1" name="deleted_architecture1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_arc"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_architecture${iPrevCount}" name="deleted_architecture${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ArchitectureRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ArchitectureUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("architecture"+i).value = "";
        document.getElementById('deleted_architecture'+i).value = 1;
    }

    function settotalrowsArchitecture() {
        var max_row_count = document.getElementById('proTabArchitec').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_architecture').val(max_row_count);
    }

    function ConstructionUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-construction','construction','input-contact_construction','contact_construction','service_level_construction','sales_construction_name','percen_com_sales_construction','construction_no','construction_name_th','construction_name_en','construction_group','construction_industry','construction_grade');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabConstruction');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_const"+iCount))
                {
                    if(document.getElementById("row_const"+iCount).style.display != 'none' && document.getElementById('deleted_const'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_const"+iCount) && document.getElementById("row_const"+iCount).style.display != 'none' && document.getElementById('deleted_const'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                TTemp = document.getElementById(sId).innerHTML;
                document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                document.getElementById(sSwapId).innerHTML = TTemp;

            }
        } 
    }

    function ConstructionRemove(i){
        var tableName = document.getElementById('proTabConstruction');
        var prev = tableName.rows.length;
        document.getElementById("row_const"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_const"+iCount) && document.getElementById("row_const"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_const"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_const"+iCount) && document.getElementById("row_const"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_const"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_const1" name="deleted_const1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_const"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_const${iPrevCount}" name="deleted_const${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ConstructionRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ConstructionUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("construction"+i).value = "";
        document.getElementById('deleted_const'+i).value = 1;
    }

    function settotalrowsConstruction() {
        var max_row_count = document.getElementById('proTabConstruction').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_construction').val(max_row_count);
    }
    
    function DesignerUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-designer','designer','input-contact_designer','contact_designer','service_level_designer','sales_designer_name','percen_com_sales_designer','designer_no','designer_name_th','designer_name_en','designer_group','designer_industry','designer_grade');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabDesigner');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_designer"+iCount))
                {
                    if(document.getElementById("row_designer"+iCount).style.display != 'none' && document.getElementById('deleted_designer'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_designer"+iCount) && document.getElementById("row_designer"+iCount).style.display != 'none' && document.getElementById('deleted_designer'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                TTemp = document.getElementById(sId).innerHTML;
                document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                document.getElementById(sSwapId).innerHTML = TTemp;

            }
        } 
    }

    function DesignerRemove(i){
        var tableName = document.getElementById('proTabDesigner');
        var prev = tableName.rows.length;
        document.getElementById("row_designer"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_designer"+iCount) && document.getElementById("row_designer"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_designer"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_designer"+iCount) && document.getElementById("row_designer"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_designer"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_designer1" name="deleted_designer1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_designer"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_designer${iPrevCount}" name="deleted_designer${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="DesignerRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="DesignerUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("designer"+i).value = "";
        document.getElementById('deleted_designer'+i).value = 1;
    }

    function settotalrowsDesigner() {
        var max_row_count = document.getElementById('proTabDesigner').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_designer').val(max_row_count);
    }

    function ContractorUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-contractor','contractor','contractor_type','input-contact_contractor','contact_contractor','service_level_contractor','sales_contractor_name','percen_com_sales_contractor','contractor_no','contractor_name_th','contractor_name_en','contractor_group','contractor_industry','contractor_grade');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabContractor');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_contractor"+iCount))
                {
                    if(document.getElementById("row_contractor"+iCount).style.display != 'none' && document.getElementById('deleted_contractor'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_contractor"+iCount) && document.getElementById("row_contractor"+iCount).style.display != 'none' && document.getElementById('deleted_contractor'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                if(aFieldIds[iCt] == 'contractor-input' || aFieldIds[iCt] == 'input-contact_contractor' ){
                    TTemp = document.getElementById(sId).innerHTML;
                    document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                    document.getElementById(sSwapId).innerHTML = TTemp;
                }
            }
        } 
    }

    function ContractorRemove(i){
        var tableName = document.getElementById('proTabContractor');
        var prev = tableName.rows.length;
        document.getElementById("row_contractor"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_contractor"+iCount) && document.getElementById("row_contractor"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_contractor"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_contractor"+iCount) && document.getElementById("row_contractor"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_contractor"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_contractor1" name="deleted_contractor1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_contractor"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_contractor${iPrevCount}" name="deleted_contractor${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ContractorRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ContractorUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("contractor"+i).value = "";
        document.getElementById('deleted_contractor'+i).value = 1;
    }

    function settotalrowsContractor() {
        var max_row_count = document.getElementById('proTabContractor').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_contractor').val(max_row_count);
    }

    function SubContractorUpDown(sType,iIndex){
       
        var aFieldIds = Array('input-subcontractor','subcontractor','sub_contractor_type','input-contact_subcontractor','contact_subcontractor','service_level_sub_contractor','sales_sub_contractor_name','percen_com_sales_sub_contractor','sub_contractor_no','sub_contractor_name_th','sub_contractor_name_en','sub_contractor_group','sub_contractor_industry','sub_contractor_grade');
        iIndex = eval(iIndex) + 1;
        var oTable = document.getElementById('proTabSubContractor');
        iMax = oTable.rows.length;
        iSwapIndex = 1;
        if(sType == 'UP')
        { 
            for(iCount=iIndex-2;iCount>=1;iCount--)
            {
                if(document.getElementById("row_subcontractor"+iCount))
                {
                    if(document.getElementById("row_subcontractor"+iCount).style.display != 'none' && document.getElementById('deleted_subcontractor'+iCount).value == 0)
                    {
                        iSwapIndex = iCount+1;
                        break;
                    }
                }
            }
        }
        else
        {
            for(iCount=iIndex;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_subcontractor"+iCount) && document.getElementById("row_subcontractor"+iCount).style.display != 'none' && document.getElementById('deleted_subcontractor'+iCount).value == 0)
                {
                    iSwapIndex = iCount;
                    break;
                }
            }
            iSwapIndex += 1;
        }
    
        iIndex -= 1;
        iSwapIndex -= 1;
        
        iMaxElement = aFieldIds.length;
        for(iCt=0;iCt<iMaxElement;iCt++)
        {
            sId = aFieldIds[iCt] + iIndex;
            sSwapId = aFieldIds[iCt] + iSwapIndex;
            if(document.getElementById(sId) && document.getElementById(sSwapId))
            {
                sTemp = document.getElementById(sId).value;
                document.getElementById(sId).value = document.getElementById(sSwapId).value;
                document.getElementById(sSwapId).value = sTemp;

                if(aFieldIds[iCt] == 'input-subcontractor' || aFieldIds[iCt] == 'input-contact_subcontractor'){
                    TTemp = document.getElementById(sId).innerHTML;
                    document.getElementById(sId).innerHTML = document.getElementById(sSwapId).innerHTML;
                    document.getElementById(sSwapId).innerHTML = TTemp;
                }

            }
        } 
    }

    function SubContractorRemove(i){
        var tableName = document.getElementById('proTabSubContractor');
        var prev = tableName.rows.length;
        document.getElementById("row_subcontractor"+i).style.display = 'none';

        iMax = tableName.rows.length;
        for(iCount=i;iCount>=1;iCount--)
        {
            if(document.getElementById("row_subcontractor"+iCount) && document.getElementById("row_subcontractor"+iCount).style.display != 'none')
            {
                iPrevRowIndex = iCount;
                break;
            }
        }
        
        iPrevCount = iPrevRowIndex;
        oCurRow = eval(document.getElementById("row_subcontractor"+i));
        sTemp = oCurRow.cells[0].innerHTML;
        ibFound = sTemp.indexOf("show_button_down");
        if(iPrevCount == 1){
           iSwapIndex = i;
            for(iCount=i;iCount<=iMax-1;iCount++)
            {
                if(document.getElementById("row_subcontractor"+iCount) && document.getElementById("row_subcontractor"+iCount).style.display != 'none')
                {
                    iSwapIndex = iCount;
                    break;
                }
            }   
            if(iSwapIndex == i)
            {
                oPrevRow = eval(document.getElementById("row_subcontractor"+iPrevCount));
                iPrevCount = eval(iPrevCount);
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_subcontractor1" name="deleted_subcontractor1" value="0">`;
            } 
        }else if(iPrevCount != 1 && ibFound == -1 && i != 2){
            oPrevRow = eval(document.getElementById("row_subcontractor"+iPrevCount));
            iPrevCount = eval(iPrevCount);
            
            oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_subcontractor${iPrevCount}" name="deleted_subcontractor${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="SubContractorRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="SubContractorUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
        }
        document.getElementById("subcontractor"+i).value = "";
        document.getElementById('deleted_subcontractor'+i).value = 1;
    }

    function settotalrowsSubContractor() {
        var max_row_count = document.getElementById('proTabSubContractor').rows.length;
            max_row_count = eval(max_row_count)-1;
        $('#num_subcontractor').val(max_row_count);
    }
    
    $(function(){
        $('#page-top').scrollspy({ target: '#verticalScrollspy' })

        var myDate = new Date();
        var date = ('0'+ myDate.getDate()).slice(-2) + '/' +  ('0'+ (myDate.getMonth()+1)).slice(-2) + '/' + myDate.getFullYear() ;
        $("#project_open_date").val(date);

        $('.btn_add_product').click(function(i){
            var tableName = document.getElementById('proTab');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row"+iCount) && document.getElementById("row"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted${count}" name="deleted${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="PrdRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="PrdUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted${iPrevCount}" name="deleted${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="PrdRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="PrdUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="PrdUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted${iPrevCount}" name="deleted${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="PrdUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'productid${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Products','modal' => 'productid${count}','fieldName'=> 'input-productid${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'productinventory'] ); ?>
                <div class="mb-2">
                    <textarea class="base-input " id="descriptions${count}" name="descriptions${count}" rows="2"></textarea>
                </div>`

            colthree.innerHTML=`<input type="text" class="base-input base-input-text" id="product_brand${count}" name="product_brand${count}" readonly value="">`;

            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="product_group${count}" name="product_group${count}" readonly value="">`;

            colfive.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'dealerid${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'dealerid${count}','fieldName'=> 'input-dealerid${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'productinventory'] ); ?>`;

            colsix.innerHTML = `<div class="base-input-group">
                                    <input type="text" class="base-input-text" id="first_delivered_date${count}" value="" name="first_delivered_date${count}" readonly placeholder="DD/MM/YYYY">
                                    <div class="base-input-group-action">
                                        <i class="ph-calendar-blank cursor-pointer" for="first_delivered_date${count}"></i>
                                    </div>
                                </div>`;

            //const elem1 = document.querySelector(`input[name="first_delivered_date${count}"]`);
            //const datepicker1 = new Datepicker(elem1, { format: 'dd/mm/yyyy' });
            
            colseven.innerHTML = `<div class="base-input-group">
                                    <input type="text" class="base-input-text" id="last_delivered_date${count}" value="" name="last_delivered_date${count}" readonly placeholder="DD/MM/YYYY">
                                    <div class="base-input-group-action">
                                        <i class="ph-calendar-blank cursor-pointer" for="last_delivered_date${count}"></i>
                                    </div>
                                </div>`;
            //const elem2 = document.querySelector(`input[name="last_delivered_date${count}"]`);
            //const datepicker2 = new Datepicker(elem2, {format: 'dd/mm/yyyy'});

            coleight.innerHTML = `<span id="label-estimated${count}" style="display: none;">Est</span><input type="text" class="base-input" id="estimated${count}" name="estimated${count}" value="" onkeyup="calcTotal();" onkeypress=" return isNumberPricelist(event);" >`;
           
            colnine.innerHTML = `<input type="text" class="base-input " id="listPrice${count}" name="listPrice${count}" value="" onkeypress=" return isNumberPricelist(event);">`;
            
            settotalrowsPro();

            return count;
        })

        $('.btn_add_compeitor').click(function(i){
            var tableName = document.getElementById('proTabCom');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_com"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_com"+iCount) && document.getElementById("row_com"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_com${count}" name="deleted_com${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ComRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ComUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_com${iPrevCount}" name="deleted_com${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ComRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ComUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ComUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_com${iPrevCount}" name="deleted_com${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ComUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'competitorproductid${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Competitorproduct','modal' => 'competitorproductid${count}','fieldName'=> 'input-competitorproductid${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'competitorinventory'] ); ?>
                <div class="mb-2">
                    <textarea class="base-input " id="descriptions_com${count}" name="descriptions_com${count}" rows="2"></textarea>
                </div>`

            colthree.innerHTML=`<input type="text" class="base-input base-input-text" id="competitor_brand${count}" name="competitor_brand${count}" readonly value="">`;

            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="comprtitor_product_group${count}" name="comprtitor_product_group${count}" readonly value="">`;

            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="comprtitor_product_size${count}" name="comprtitor_product_size${count}" readonly value="">`;

            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="comprtitor_product_thickness${count}" name="comprtitor_product_thickness${count}" readonly value="">`;

            colseven.innerHTML = `<input type="text" class="base-input" id="comprtitor_estimated_unit${count}" name="comprtitor_estimated_unit${count}" onkeypress=" return isNumberPricelist(event);" value="">`;

            coleight.innerHTML = `<input type="text" class="base-input" id="competitor_price${count}" name="competitor_price${count}" value="">`;
            
            settotalrowsCom();
            return count;
        })

        $('.btn_add_owner').click(function(i){
            var tableName = document.getElementById('proTabOwner');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_owner"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);

            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);
            var colten = row.insertCell(9);
            var coleleven = row.insertCell(10);
            var coltwelve = row.insertCell(11);
            
            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_owner"+iCount) && document.getElementById("row_owner"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_owner${count}" name="deleted_owner${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="OwnerRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="OwnerUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_owner${iPrevCount}" name="deleted_owner${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="OwnerRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="OwnerUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="OwnerUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_owner${iPrevCount}" name="deleted_owner${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="OwnerUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'owner${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'owner${count}','fieldName'=> 'input-owner${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'ownerinventory'] ); ?>`;

            
            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_no${count}" name="owner_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_name_th${count}" name="owner_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_name_en${count}" name="owner_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_group${count}" name="owner_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_industry${count}" name="owner_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_grade${count}" name="owner_grade${count}" readonly value="">`
    

            colnine.innerHTML= `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_owner${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_owner${count}','fieldName'=> 'input-contact_owner${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'ownerinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_owner${count}" name="service_level_owner${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_owner_name${count}" name="sales_owner_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_owner${count}" name="percen_com_sales_owner${count}" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">`;

            settotalrowsOwner();
            return count;
        })
        
        $('.btn_add_consultant').click(function(i){
            var tableName = document.getElementById('proTabConsultant');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_consul"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            
            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);
            var colten = row.insertCell(9);
            var coleleven = row.insertCell(10);
            var coltwelve = row.insertCell(11);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_consul"+iCount) && document.getElementById("row_consul"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_consultant${count}" name="deleted_consultant${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ConsultantRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ConsultantUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_consultant${iPrevCount}" name="deleted_consultant${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ConsultantRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ConsultantUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ConsultantUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_consultant${iPrevCount}" name="deleted_consultant${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ConsultantUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'consultant${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'consultant${count}','fieldName'=> 'input-consultant${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'consultantinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_no${count}" name="consultant_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_name_th${count}" name="consultant_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_name_en${count}" name="consultant_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_group${count}" name="consultant_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_industry${count}" name="consultant_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_grade${count}" name="consultant_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_consultant${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_consultant${count}','fieldName'=> 'input-contact_consultant${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'consultantinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_consultant${count}" name="service_level_consultant${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_consultant_name${count}" name="sales_consultant_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_consultant${count}" name="percen_com_sales_consultant${count}" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">`;

            settotalrowsConsultant();
            return count;
        })
    
        $('.btn_add_architecture').click(function(i){
            var tableName = document.getElementById('proTabArchitec');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_arc"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            
            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);
            var colten = row.insertCell(9);
            var coleleven = row.insertCell(10);
            var coltwelve = row.insertCell(11);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_arc"+iCount) && document.getElementById("row_arc"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_architecture${count}" name="deleted_architecture${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ArchitectureRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ArchitectureUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_architecture${iPrevCount}" name="deleted_architecture${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ArchitectureRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ArchitectureUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ArchitectureUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_architecture${iPrevCount}" name="deleted_architecture${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ArchitectureUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'architecture${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'architecture${count}','fieldName'=> 'input-architecture${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'architectureinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_no${count}" name="architecture_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_name_th${count}" name="architecture_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_name_en${count}" name="architecture_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_group${count}" name="architecture_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_industry${count}" name="architecture_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_grade${count}" name="architecture_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_architecture${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_architecture${count}','fieldName'=> 'input-contact_architecture${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'architectureinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_architecture${count}" name="service_level_architecture${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_architecture_name${count}" name="sales_architecture_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_architecture${count}" name="percen_com_sales_architecture${count}" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">`;

            settotalrowsArchitecture();
            return count;
        })

        $('.btn_add_construction').click(function(i){
            var tableName = document.getElementById('proTabConstruction');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_const"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            
            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);
            var colten = row.insertCell(9);
            var coleleven = row.insertCell(10);
            var coltwelve = row.insertCell(11);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_const"+iCount) && document.getElementById("row_const"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_const${count}" name="deleted_const${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ConstructionRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ConstructionUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_const${iPrevCount}" name="deleted_const${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ConstructionRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ConstructionUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ConstructionUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_const${iPrevCount}" name="deleted_const${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ConstructionUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'construction${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'construction${count}','fieldName'=> 'input-construction${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'constructioninventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_no${count}" name="construction_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_name_th${count}" name="construction_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_name_en${count}" name="construction_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_group${count}" name="construction_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_industry${count}" name="construction_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_grade${count}" name="construction_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_construction${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_construction${count}','fieldName'=> 'input-contact_construction${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'constructioninventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_construction${count}" name="service_level_construction${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_construction_name${count}" name="sales_construction_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_construction${count}" name="percen_com_sales_construction${count}" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">`;

            settotalrowsConstruction();
            return count;
        })
    
        $('.btn_add_designer').click(function(i){
            var tableName = document.getElementById('proTabDesigner');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_designer"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            
            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);
            var colten = row.insertCell(9);
            var coleleven = row.insertCell(10);
            var coltwelve = row.insertCell(11);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_designer"+iCount) && document.getElementById("row_designer"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_designer${count}" name="deleted_designer${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="DesignerRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="DesignerUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_designer${iPrevCount}" name="deleted_designer${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="DesignerRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="DesignerUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="DesignerUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_designer${iPrevCount}" name="deleted_designer${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="DesignerUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'designer${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'designer${count}','fieldName'=> 'input-designer${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'designerinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_no${count}" name="designer_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_name_th${count}" name="designer_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_name_en${count}" name="designer_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_group${count}" name="designer_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_industry${count}" name="designer_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_grade${count}" name="designer_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_designer${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_designer${count}','fieldName'=> 'input-contact_designer${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'designerinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_designer${count}" name="service_level_designer${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_designer_name${count}" name="sales_designer_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_designer${count}" name="percen_com_sales_designer${count}" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">`;

            settotalrowsDesigner();
            return count;
        })

        $('.btn_add_contractor').click(function(i){
            var tableName = document.getElementById('proTabContractor');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_contractor"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            var colseven = row.insertCell(6);

            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);
            var colten = row.insertCell(9);
            var coleleven = row.insertCell(10);
            var coltwelve = row.insertCell(11);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_contractor"+iCount) && document.getElementById("row_contractor"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_contractor${count}" name="deleted_contractor${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ContractorRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ContractorUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_contractor${iPrevCount}" name="deleted_contractor${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="ContractorRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="ContractorUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ContractorUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_contractor${iPrevCount}" name="deleted_contractor${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ContractorUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'contractor${count}','fieldName'=> 'input-contractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'contractorinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_no${count}" name="contractor_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_name_th${count}" name="contractor_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_name_en${count}" name="contractor_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_group${count}" name="contractor_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_industry${count}" name="contractor_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_grade${count}" name="contractor_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_contractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_contractor${count}','fieldName'=> 'input-contact_contractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'contractorinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_contractor${count}" name="service_level_contractor${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_contractor_name${count}" name="sales_contractor_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_contractor${count}" name="percen_com_sales_contractor${count}" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">`;

            settotalrowsContractor();
            return count;
        })

        $('.btn_add_subcontractor').click(function(i){
            var tableName = document.getElementById('proTabSubContractor');
            var prev = tableName.rows.length;
            var count = eval(prev);
            var row = tableName.insertRow(prev);
            row.id = "row_subcontractor"+count;
            row.className = "row_data";
            var colone = row.insertCell(0);
            var coltwo = row.insertCell(1);
            var colthree  = row.insertCell(2);
            var colfour = row.insertCell(3);
            var colfive = row.insertCell(4);
            var colsix = row.insertCell(5);
            var colseven = row.insertCell(6);

            var colseven = row.insertCell(6);
            var coleight = row.insertCell(7);
            var colnine = row.insertCell(8);
            var colten = row.insertCell(9);
            var coleleven = row.insertCell(10);
            var coltwelve = row.insertCell(11);

            iMax = tableName.rows.length;
            for(iCount=1;iCount<=iMax-2;iCount++)
            {
                if(document.getElementById("row_subcontractor"+iCount) && document.getElementById("row_subcontractor"+iCount).style.display != 'none')
                {
                    iPrevRowIndex = iCount;
                }
            }
            iPrevCount = eval(iPrevRowIndex);
            
            var oPrevRow = tableName.rows[iPrevRowIndex];
            var delete_row_count=count;

            colone.className = "txt-center txt-middle";            
            colone.innerHTML = `<input type="hidden" id="deleted_subcontractor${count}" name="deleted_subcontractor${count}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="SubContractorRemove('${count}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="SubContractorUpDown(\'UP\','${count}');"></i>
                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                    </div>
                                </div>`;
            if(iPrevCount != 1)
            {
                oPrevRow.cells[0].innerHTML = `<input type="hidden" id="deleted_subcontractor${iPrevCount}" name="deleted_subcontractor${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-trash" onclick="SubContractorRemove('${iPrevCount}');"></i>
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up" title="Up" onclick="SubContractorUpDown(\'UP\','${iPrevCount}');"></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="SubContractorUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;
            }
            else
            {
                oPrevRow.cells[0].innerHTML =`<input type="hidden" id="deleted_subcontractor${iPrevCount}" name="deleted_subcontractor${iPrevCount}" value="0">
                                <div class="row">
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        
                                    </div>
                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                        <i class="ph-arrow-up disable_button" title="Up" ></i>
                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="SubContractorUpDown(\'DOWN\','${iPrevCount}');" ></i>
                                    </div>
                                </div>`;

            }

            coltwo.innerHTML = `<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'subcontractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'subcontractor${count}','fieldName'=> 'input-subcontractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'subcontractorinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_no${count}" name="sub_contractor_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_name_th${count}" name="sub_contractor_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_name_en${count}" name="sub_contractor_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_group${count}" name="sub_contractor_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_industry${count}" name="sub_contractor_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_grade${count}" name="sub_contractor_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_subcontractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_subcontractor${count}','fieldName'=> 'input-contact_subcontractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'subcontractorinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_sub_contractor${count}" name="service_level_sub_contractor${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_sub_contractor_name${count}" name="sales_sub_contractor_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_sub_contractor${count}" name="percen_com_sales_sub_contractor${count}" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);" value="">`;

            settotalrowsSubContractor();
            return count;
        })

        $('.list-item-bar').click(function(i){
            $('.list-item-bar').removeClass('active');
            $(this).addClass('active');
        })

        $('.check-assign').change(function(i, e){
            const eleID = $('input[name="assign_to"]:checked').attr('id');
            $('.check-assign-list').hide();
            $(`#${eleID}-list`).show()
        })

        $('.input-popup-search').keypress(function(e){
            if(e.which == 13){//Enter key pressed
                var len = $(this).val().length
                var fieldID = $(this).data('field')
                var moduleSelect = $(this).data('moduleselect')
                var filter = $(`#${fieldID}-modal-search-box`).val()
                var selectfield = $(`#${fieldID}-modal-select-box`).val()

                var relate_field_down = $(this).data('relate-field-down')
                var relate_field_up = $(this).data('relate-field-up')

                getPopupList(moduleSelect, fieldID, filter, selectfield, 1, relate_field_up, relate_field_down)
            }
        })

        $('.input-pagenumber').change(function(i, e){
            var fieldID = $(this).data('field')
            var moduleSelect = $(this).data('moduleselect')

            var filter = $(`#${fieldID}-modal-search-box`).val()
            var selectfield = $(`#${fieldID}-modal-select-box`).val()
            var Page = $(`#page-num-${fieldID}`).val()
            var totalPage = $(`#page-of-${fieldID}`).html()
            
            var relate_field_down = $(this).data('relate-field-down')
            var relate_field_up = $(this).data('relate-field-up')

            if(Page > eval(totalPage)){
                $(`#list-${moduleSelect}-${fieldID}`).html('')
                return false
            }
            getPopupList(moduleSelect, fieldID, filter, selectfield, eval(Page), relate_field_up, relate_field_down)
        })
        
        $.CancelForm = function(){
            console.log(this);
        }

        $.submitForm = function(){
            $('#form-projects').submit();
        }

        $('#form-projects').submit(async function(e) {
            const return_module = '<?php echo $return_module?>'
            // console.log('form submit '+return_module)
            // return false
            e.preventDefault();

            var validCom = validTotalCom();
            if(!validCom) return false

            var form = $(this);
            form.find(':input').prop('disabled', false);
            var formData = form.serializeObject()
            var isCustomerInfo = false
            // console.log(formData); 
            for (var key in formData){
                var required = $(`#${key}`).prop('required')
                var fieldLabel = $(`#label-${key}`).html()
                var fieldValue = formData[key]

                $(`#${key}`).removeClass('input-error')
                // console.log(key, required, fieldValue)
                if(required && (fieldValue==='' || fieldValue==='--None--')){
                    $(`#${key}`).addClass('input-error').focus()
                    $('html, body').animate({ scrollTop: $(`#${key}`).offset().top - 100 }, 500);
                    Swal.fire('', `${fieldLabel} is required`, 'warning')
                    $(':input[readonly="readonly"]').prop("disabled",true);
                    return false;
                }

                if(['designer1', 'architecture1', 'owner1', 'consultant1', 'construction1', 'contractor1', 'subcontractor1'].includes(key)){
                    if(fieldValue != '') isCustomerInfo = true
                }
            }

            if(!isCustomerInfo){
                Swal.fire('', `Customer Information required 1 item`, 'warning')
                return false;
            }            

            formData.action = 'add'
            $('.overlay').show();
            await $.post('<?php echo site_url('Projects/checkDup'); ?>', formData, async function(res){
                if(res?.count == '0'){
                    await $.post('<?php echo site_url('Projects/save?userid='.$this->session->userdata('userID')); ?>', formData, async function(rs){
                        if(rs.status === 'Success'){
                            if(return_module == ''){
                                window.location.href = `<?php echo site_url('Projects/view_web'); ?>/${rs.data.Crmid}?userid=${rs.userID}`
                            } else {
                                parent.closeModal()
                                $('.overlay').hide();
                            }                            
                        } else {
                            $('.overlay').hide();
                            Swal.fire('', rs.message, 'error')
                        }
                    },'json')
                }else {
                    $('.overlay').hide();
                    Swal.fire('', 'ชื่อโครงการ + สถานที่ตั้งโครงการ มีอยู่ในระบบแล้ว', 'error')
                }
            }, 'json')
        })
    })
</script>