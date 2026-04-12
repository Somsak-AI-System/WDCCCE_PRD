<?php $hide_blocks = array('Owner/Developer Information 1','Owner/Developer Information 2','Owner/Developer Information 3','Owner/Developer Information 4','Consultant Information','Construction Information 1','Construction Information 2','Architecture Information 1','Architecture Information 2','Designer Information 1','Designer Information 2','Designer Information 3','Designer Information 4','Contractor Information 1','Contractor Information 2','Contractor Information 3','Contractor Information 4','Contractor Information 5','Contractor Information 6','Contractor Information 7','Contractor Information 8','Contractor Information 9','Contractor Information 10','Sub Contractor Information 1','Sub Contractor Information 2','Sub Contractor Information 3','Comment Information'); ?>

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

<!-- Page Content -->
<div class="page-content mb-65">
    
    <input type="hidden" name="projectorder_status" id="projectorder_status" value="<? echo @$status[0]['projectorder_status']; ?>">
    <?php if(is_array($blocks)){ foreach($blocks as $index => $block) { ?>
        <?php if(!in_array($block['header_name'], $hide_blocks)){ ?>
            <div class="card-box mb-10" id="<?php echo "block_name_".$index; ?>">
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
                            <?php echo inputView($field); ?>
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
                                        
                                        <?php if(!empty($itemList['products'])){ $est=0; $plan=0; $remain=0; $delivered=0; $sum_onhand_total=0;?>
                                            <?php foreach ($itemList['products'] as $key => $value) { 
                                                $p_row = ($key+1);
                                                $est +=  (int)$value['estimated'];
                                                $plan +=  (int)$value['plan'];
                                                $remain +=  (int)$value['remain_on_hand'];
                                                $delivered +=  (int)$value['delivered']; 
                                            ?>
                                                <tr id="row<?php echo $p_row; ?>" class="row_data">
                                                    <td class="txt-center txt-middle">
                                                        <label><?php echo $value['sequence_no']; ?></label>
                                                    </td>
                                                    <td>
                                                        <div class="mb-2">
                                                            <input type="text" class="base-input base-input-text" id="productname<?php echo $p_row; ?>" name="productname<?php echo $p_row; ?>" readonly value="<?php echo $value['productname'];?>" title="<?php echo $value['productname'];?>">
                                                            <textarea class="base-input base-input-text mt-5" id="descriptions<?php echo $p_row; ?>" name="descriptions<?php echo $p_row; ?>" rows="2" readonly  title="<?php echo $value['comment'];?>"><?php echo $value['comment'];?></textarea>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="product_brand<?php echo $p_row; ?>" name="product_brand<?php echo $p_row; ?>" readonly value="<?php echo $value['product_brand'];?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="product_group<?php echo $p_row; ?>" name="product_group<?php echo $p_row; ?>" readonly value="<?php echo $value['product_group'];?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="accountname<?php echo $p_row; ?>" name="accountname<?php echo $p_row; ?>" readonly value="<?php echo $value['accountname'];?>" title="<?php echo $value['accountname'];?>">
                                                    </td>

                                                    <td style="display: flex;text-align: center;">
                                                        <div role="button" class="btn-p" onclick="$.addProductPlan('<?php echo $value['productid'];?>','<?php echo $value['id'];?>','<?php echo $value['lineitem_id'];?>')">P</div>
                                                        <div role="button" class="btn-d" onclick="$.addProductDelivered('<?php echo $value['productid'];?>','<?php echo $value['id'];?>','<?php echo $value['accountname'];?>','<?php echo $value['accountid'];?>','<?php echo $value['lineitem_id'];?>')">D</div>
                                                    </td>
                                                    <?php 
                                                        $DateStart = '';
                                                        $DateEnd = '';

                                                        if($value['first_delivered_date'] != '1970-01-01'){
                                                            $objectDate = explode("-",$value['first_delivered_date']);
                                                            $DateStart =  $objectDate[2].'/'.$objectDate[1].'/'.$objectDate[0];
                                                        }
                                                        if($value['last_delivered_date'] != '1970-01-01'){
                                                            $objectDate = explode("-",$value['last_delivered_date']);
                                                            $DateEnd =  $objectDate[2].'/'.$objectDate[1].'/'.$objectDate[0];
                                                        }
                                                    ?>
                                                    <td>
                                                       <div class="base-input-group">
                                                            <input type="text" class="base-input-text first_delivered-<?php echo $value['lineitem_id']; ?>" id="first_delivered_date<?php echo $p_row; ?>" value="<?php echo $DateStart;?>" name="first_delivered_date<?php echo $p_row; ?>" readonly placeholder="DD/MM/YYYY">
                                                        </div> 
                                                    </td>
                                                    <td>
                                                        <div class="base-input-group">
                                                            <input type="text" class="base-input-text last_delivered-<?php echo $value['lineitem_id']; ?>" id="last_delivered_date<?php echo $p_row; ?>" value="<?php echo $DateEnd;?>" name="last_delivered_date<?php echo $p_row; ?>" readonly placeholder="DD/MM/YYYY">
                                                        </div> 
                                                    </td>
                                                    
                                                    <td>
                                                        <input type="text" class="base-input base-input-text estimated estimated-<?php echo $value['lineitem_id'];?>" id="estimated<?php echo $value['lineitem_id'];?>" name="estimated<?php echo $value['sequence_no'];?>" readonly value="<?php echo number_format($value['estimated'],0);?>" title="<?php echo number_format($value['estimated'],0);?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text plan plan-<?php echo $value['lineitem_id'];?>" id="plan<?php echo $value['sequence_no'];?>" name="plan<?php echo $value['sequence_no'];?>" readonly value="<?php echo number_format($value['plan'],0);?>" title="<?php echo number_format($value['plan'],0);?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text delivered delivered-<?php echo $value['lineitem_id'];?>" id="delivered<?php echo $value['sequence_no'];?>" name="delivered<?php echo $value['sequence_no'];?>" readonly value="<?php echo number_format($value['delivered'],0);?>" title="<?php echo number_format($value['delivered'],0);?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text remain_on_hand remain_on_hand-<?php echo $value['lineitem_id'];?>" id="remain_on_hand<?php echo $value['sequence_no'];?>" name="remain_on_hand<?php echo $value['sequence_no'];?>" readonly value="<?php echo number_format($value['remain_on_hand'],0);?>" title="<?php echo number_format($value['remain_on_hand'],0);?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="listPrice<?php echo $p_row; ?>" name="listPrice<?php echo $p_row; ?>" value="<?php echo $value['listprice'];?>" readonly>
                                                    </td>
                                                    <?php 
                                                        $onhand_total = $value['remain_on_hand'] * $value['listprice'];
                                                        $sum_onhand_total = $sum_onhand_total + $onhand_total;
                                                    ?>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="onhand_total<?php echo $p_row; ?>" name="onhand_total<?php echo $p_row; ?>" value="<?php echo $onhand_total;?>" readonly>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            
                                                <tr class="font-14 font-bold" style="background-color: #F8FAFC !important;">
                                                    <td class="pd-10 border-top-radius-5"></td>
                                                    <td class="pd-10 "></td>
                                                    <td class="pd-10 "></td>
                                                    <td class="pd-10 "></td>
                                                    <td class="pd-10 "></td>
                                                    <td class="pd-10 "></td>
                                                    <td class="pd-10 "></td>
                                                    
                                                    <td class="v-align-middle" align="right">
                                                        <span class="font-16 font-bold">Total</span></td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="total_est" name="total_est" readonly value="<?php echo $est;?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="total_plan" name="total_plan" readonly value="<?php echo $plan;?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="total_deli" name="total_deli" readonly value="<?php echo $delivered;?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="total_on_hand" name="total_on_hand" readonly value="<?php echo $remain;?>">
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <input type="text" class="base-input base-input-text" id="sum_onhand_total" name="sum_onhand_total" readonly value="<?php echo $sum_onhand_total;?>">
                                                    </td>
                                                </tr>
                                            
                                            </table>
                                        <?php }?>
                                </div>
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
                                        <?php if(!empty($itemList['competitor'])){?>
                                            <?php foreach ($itemList['competitor'] as $key => $value) { 
                                                $com_row = ($key+1); ?>
                                                    <tr id="row_com<?php echo $com_row; ?>" class="row_data">
                                                        <td class="txt-center txt-middle">
                                                            <label><?php echo $value['sequence_no'];?></label>
                                                        </td>
                                                            
                                                        <td>
                                                           <input type="text" class="base-input base-input-text" id="competitorproduct_name<?php echo $com_row;?>" name="competitorproduct_name<?php echo $com_row;?>" readonly value="<?php echo $value['competitorproduct_name_th'];?>" title="<?php echo $value['competitorproduct_name_th'];?>">
                                                            <div class="mb-2">
                                                                <textarea class="base-input base-input-text mt-5" id="descriptions_com<?php echo $com_row;?>" name="descriptions_com$<?php echo $com_row;?>" rows="2" title="<?php echo $value['competitorcomment'];?>" readonly><?php echo $value['competitorcomment'];?></textarea>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="base-input base-input-text" id="competitor_brand<?php echo $com_row;?>" name="competitor_brand<?php echo $com_row;?>" readonly value="<?php echo $value['competitor_brand'];?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="base-input base-input-text" id="comprtitor_product_group<?php echo $com_row;?>" name="comprtitor_product_group<?php echo $com_row;?>" readonly value="<?php echo $value['comprtitor_product_group'];?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="base-input base-input-text" id="comprtitor_product_size<?php echo $com_row;?>" name="comprtitor_product_size<?php echo $com_row;?>" readonly value="<?php echo $value['comprtitor_product_size'];?>">
                                                        </td>

                                                        <td>
                                                           <input type="text" class="base-input base-input-text" id="comprtitor_product_thickness<?php echo $com_row;?>" name="comprtitor_product_thickness<?php echo $com_row;?>" readonly value="<?php echo $value['comprtitor_product_thickness'];?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="base-input base-input-text" id="comprtitor_estimated_unit<?php echo $com_row;?>" name="comprtitor_estimated_unit<?php echo $com_row;?>" readonly value="<?php echo number_format($value['comprtitor_estimated_unit'],0);?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="base-input base-input-text" id="competitor_price<?php echo $com_row;?>" name="competitor_price<?php echo $com_row;?>" readonly value="<?php echo $value['competitor_price'];?>">
                                                        </td>
                                                    </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </table>
                                </div>
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
                                    <?php if(!empty($itemList['designer'])){?>
                                        <?php foreach ($itemList['designer'] as $key => $value) { 
                                            $des_row = ($key+1); ?>
                                            <tr id="row_designer<?php echo $des_row;?>" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <label><?php echo $value['sequence_no']; ?></label>
                                                </td>
                                                <td>
                                                   <input type="text" class="base-input base-input-text" id="accountname<?php echo $des_row;?>" name="accountname<?php echo $des_row;?>" readonly value="<?php echo $value['accountname'];?>" title="<?php echo $value['accountname'];?>"> 
                                                </td>
                                                <td><input type="text" class="base-input base-input-text" id="designer_no<?php echo $des_row;?>" name="designer_no<?php echo $des_row;?>" readonly value="<?php echo $value['designer_no'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="designer_name_th<?php echo $des_row;?>" name="designer_name_th<?php echo $des_row;?>" readonly value="<?php echo $value['designer_name_th'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="designer_name_en<?php echo $des_row;?>" name="designer_name_en<?php echo $des_row;?>" readonly value="<?php echo $value['designer_name_en'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="designer_group<?php echo $des_row;?>" name="designer_group<?php echo $des_row;?>" readonly value="<?php echo $value['designer_group'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="designer_industry<?php echo $des_row;?>" name="designer_industry<?php echo $des_row;?>" readonly value="<?php echo $value['designer_industry'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="designer_grade<?php echo $des_row;?>" name="designer_grade<?php echo $des_row;?>" readonly value="<?php echo $value['designer_grade'];?>"></td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="contactname<?php echo $des_row;?>" name="contactname<?php echo $des_row;?>" readonly value="<?php echo $value['contactname'];?>" title="<?php echo $value['contactname'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="service_level_designer<?php echo $des_row;?>" name="service_level_designer<?php echo $des_row;?>" readonly value="<?php echo $value['service_level_designer'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="sales_designer_name<?php echo $des_row;?>" name="sales_designer_name<?php echo $des_row;?>" readonly value="<?php echo $value['sales_designer_name'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_designer<?php echo $des_row;?>" name="percen_com_sales_designer<?php echo $des_row;?>" readonly value="<?php echo $value['percen_com_sales_designer'];?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
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
                                    <?php if(!empty($itemList['architecture'])){?>
                                        <?php foreach ($itemList['architecture'] as $key => $value) { 
                                            $arc_row = ($key+1); ?>
                                            <tr id="row_arc<?php echo $arc_row;?>" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <label><?php echo $value['sequence_no']; ?></label>
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="accountname<?php echo $arc_row;?>" name="accountname<?php echo $arc_row;?>" readonly value="<?php echo $value['accountname'];?>" title="<?php echo $value['accountname'];?>">
                                                </td>
                                                <td><input type="text" class="base-input base-input-text" id="architecture_no<?php echo $arc_row;?>" name="architecture_no<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_no'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="architecture_name_th<?php echo $arc_row;?>" name="architecture_name_th<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_name_th'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="architecture_name_en<?php echo $arc_row;?>" name="architecture_name_en<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_name_en'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="architecture_group<?php echo $arc_row;?>" name="architecture_group<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_group'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="architecture_industry<?php echo $arc_row;?>" name="architecture_industry<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_industry'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="architecture_grade<?php echo $arc_row;?>" name="architecture_grade<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_grade'];?>"></td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="contactname<?php echo $arc_row;?>" name="contactname<?php echo $arc_row;?>" readonly value="<?php echo $value['contactname'];?>" title="<?php echo $value['contactname'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="service_level_architecture<?php echo $arc_row;?>" name="service_level_architecture<?php echo $arc_row;?>" readonly value="<?php echo $value['service_level_architecture'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="sales_architecture_name<?php echo $arc_row;?>" name="sales_architecture_name<?php echo $arc_row;?>" readonly value="<?php echo $value['sales_architecture_name'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_architecture<?php echo $arc_row;?>" name="percen_com_sales_architecture<?php echo $arc_row;?>" readonly value="<?php echo $value['percen_com_sales_architecture'];?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?> 
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
                                    <?php if(!empty($itemList['owner'])){?>
                                        <?php foreach ($itemList['owner'] as $key => $value) { 
                                            $owner_row = ($key+1); ?>
                                            <tr id="row_owner<?php echo $owner_row; ?>" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <label><?php echo $value['sequence_no']; ?></label>
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="accountname<?php echo $owner_row;?>" name="accountname<?php echo $owner_row;?>" readonly value="<?php echo $value['accountname'];?>" title="<?php echo $value['accountname'];?>">
                                                </td>
                                                <td><input type="text" class="base-input base-input-text" id="owner_no<?php echo $owner_row;?>" name="owner_no<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_no'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="owner_name_th<?php echo $owner_row;?>" name="owner_name_th<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_name_th'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="owner_name_en<?php echo $owner_row;?>" name="owner_name_en<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_name_en'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="owner_group<?php echo $owner_row;?>" name="owner_group<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_group'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="owner_industry<?php echo $owner_row;?>" name="owner_industry<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_industry'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="owner_grade<?php echo $owner_row;?>" name="owner_grade<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_grade'];?>"></td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="contactname<?php echo $owner_row;?>" name="contactname<?php echo $owner_row;?>" readonly value="<?php echo $value['contactname'];?>" title="<?php echo $value['contactname'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="service_level_owner<?php echo $owner_row;?>" name="service_level_owner<?php echo $owner_row;?>" readonly value="<?php echo $value['service_level_owner'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="sales_owner_name<?php echo $owner_row;?>" name="sales_owner_name<?php echo $owner_row;?>" readonly value="<?php echo $value['sales_owner_name'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_owner<?php echo $owner_row;?>" name="percen_com_sales_owner<?php echo $owner_row;?>" readonly value="<?php echo $value['percen_com_sales_owner'];?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
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
                                    <?php if(!empty($itemList['consultant'])){?>
                                        <?php foreach ($itemList['consultant'] as $key => $value) { 
                                            $con_row = ($key+1); ?>
                                            <tr id="row_consul<?php echo $con_row; ?>" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <label><?php echo $value['sequence_no']; ?></label>
                                                </td>
                                                    
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="accountname<?php echo $con_row; ?>" name="accountname<?php echo $con_row; ?>" readonly value="<?php echo $value['accountname']; ?>" title="<?php echo $value['accountname']; ?>">
                                                </td>
                                                <td><input type="text" class="base-input base-input-text" id="consultant_no<?php echo $con_row; ?>" name="consultant_no<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_no']; ?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="consultant_name_th<?php echo $con_row; ?>" name="consultant_name_th<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_name_th']; ?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="consultant_name_en<?php echo $con_row; ?>" name="consultant_name_en<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_name_en']; ?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="consultant_group<?php echo $con_row; ?>" name="consultant_group<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_group']; ?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="consultant_industry<?php echo $con_row; ?>" name="consultant_industry<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_industry']; ?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="consultant_grade<?php echo $con_row; ?>" name="consultant_grade<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_grade']; ?>"></td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="contactname<?php echo $con_row; ?>" name="contactname<?php echo $con_row; ?>" readonly value="<?php echo $value['contactname']; ?>" title="<?php echo $value['contactname']; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="service_level_consultant<?php echo $con_row; ?>" name="service_level_consultant<?php echo $con_row; ?>" readonly value="<?php echo $value['service_level_consultant']; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="sales_consultant_name<?php echo $con_row; ?>" name="sales_consultant_name<?php echo $con_row; ?>" readonly value="<?php echo $value['sales_consultant_name']; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_consultant<?php echo $con_row; ?>" name="percen_com_sales_consultant<?php echo $con_row; ?>" readonly value="<?php echo $value['percen_com_sales_consultant']; ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?> 
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
                                    <?php if(!empty($itemList['construction'])){?>
                                        <?php foreach ($itemList['construction'] as $key => $value) { 
                                            $cons_row = ($key+1); ?>
                                            <tr id="row_const<?php echo $cons_row;?>" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <label><?php echo $value['sequence_no']; ?></label>
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="accountname<?php echo $cons_row;?>" name="accountname<?php echo $cons_row;?>" readonly value="<?php echo $value['accountname'];?>" title="<?php echo $value['accountname'];?>">
                                                </td>
                                                <td><input type="text" class="base-input base-input-text" id="construction_no<?php echo $cons_row;?>" name="construction_no<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_no'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="construction_name_th<?php echo $cons_row;?>" name="construction_name_th<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_name_th'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="construction_name_en<?php echo $cons_row;?>" name="construction_name_en<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_name_en'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="construction_group<?php echo $cons_row;?>" name="construction_group<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_group'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="construction_industry<?php echo $cons_row;?>" name="construction_industry<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_industry'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="construction_grade<?php echo $cons_row;?>" name="construction_grade<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_grade'];?>"></td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="contactname<?php echo $cons_row;?>" name="contactname<?php echo $cons_row;?>" readonly value="<?php echo $value['contactname'];?>" title="<?php echo $value['contactname'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="service_level_construction<?php echo $cons_row;?>" name="service_level_construction<?php echo $cons_row;?>" readonly value="<?php echo $value['service_level_construction'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="sales_construction_name<?php echo $cons_row;?>" name="sales_construction_name<?php echo $cons_row;?>" readonly value="<?php echo $value['sales_construction_name'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_construction<?php echo $cons_row;?>" name="percen_com_sales_construction<?php echo $cons_row;?>" readonly value="<?php echo $value['percen_com_sales_construction'];?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                   <?php } ?> 
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
                                    <?php if(!empty($itemList['contractor'])){?>
                                        <?php foreach ($itemList['contractor'] as $key => $value) { 
                                            $cont_row = ($key+1); ?>
                                            <tr id="row_contractor<?php echo $cont_row;?>" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <label><?php echo $value['sequence_no']; ?></label>
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="accountname<?php echo $cont_row;?>" name="accountname<?php echo $cont_row;?>" readonly value="<?php echo $value['accountname'];?>" title="<?php echo $value['accountname'];?>">
                                                </td>
                                                <td><input type="text" class="base-input base-input-text" id="contractor_no<?php echo $cont_row;?>" name="contractor_no<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_no'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="contractor_name_th<?php echo $cont_row;?>" name="contractor_name_th<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_name_th'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="contractor_name_en<?php echo $cont_row;?>" name="contractor_name_en<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_name_en'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="contractor_group<?php echo $cont_row;?>" name="contractor_group<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_group'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="contractor_industry<?php echo $cont_row;?>" name="contractor_industry<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_industry'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="contractor_grade<?php echo $cont_row;?>" name="contractor_grade<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_grade'];?>"></td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="contactname<?php echo $cont_row;?>" name="contactname<?php echo $cont_row;?>" readonly value="<?php echo $value['contactname'];?>" title="<?php echo $value['contactname'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="service_level_contractor<?php echo $cont_row;?>" name="service_level_contractor<?php echo $cont_row;?>" readonly value="<?php echo $value['service_level_contractor'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="sales_contractor_name<?php echo $cont_row;?>" name="sales_contractor_name<?php echo $cont_row;?>" readonly value="<?php echo $value['sales_contractor_name'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_contractor<?php echo $cont_row;?>" name="percen_com_sales_contractor<?php echo $cont_row;?>" readonly value="<?php echo $value['percen_com_sales_contractor'];?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
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
                                    <?php if(!empty($itemList['subcontractor'])){?>
                                        <?php foreach ($itemList['subcontractor'] as $key => $value) { 
                                            $subcon_row = ($key+1); ?>
                                            <tr id="row_subcontractor<?php echo $subcon_row;?>" class="row_data">
                                                <td class="txt-center txt-middle">
                                                    <label><?php echo $value['sequence_no']; ?></label>
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="accountname<?php echo $subcon_row;?>" name="accountname<?php echo $subcon_row;?>" readonly value="<?php echo $value['accountname'];?>" title="<?php echo $value['accountname'];?>">
                                                </td>
                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_no<?php echo $subcon_row;?>" name="sub_contractor_no<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_no'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_th<?php echo $subcon_row;?>" name="sub_contractor_name_th<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_name_th'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_en<?php echo $subcon_row;?>" name="sub_contractor_name_en<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_name_en'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_group<?php echo $subcon_row;?>" name="sub_contractor_group<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_group'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_industry<?php echo $subcon_row;?>" name="sub_contractor_industry<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_industry'];?>"></td>
                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_grade<?php echo $subcon_row;?>" name="sub_contractor_grade<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_grade'];?>"></td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="contactname<?php echo $subcon_row;?>" name="contactname<?php echo $subcon_row;?>" readonly value="<?php echo $value['contactname'];?>" title="<?php echo $value['contactname'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="service_level_sub_contractor<?php echo $subcon_row;?>" name="service_level_sub_contractor<?php echo $subcon_row;?>" readonly value="<?php echo $value['service_level_sub_contractor'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="sales_sub_contractor_name<?php echo $subcon_row;?>" name="sales_sub_contractor_name<?php echo $subcon_row;?>" readonly value="<?php echo $value['sales_sub_contractor_name'];?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="base-input base-input-text" id="percen_com_sales_sub_contractor<?php echo $subcon_row;?>" name="percen_com_sales_sub_contractor<?php echo $subcon_row;?>" readonly value="<?php echo $value['percen_com_sales_sub_contractor'];?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <!-- Sub Contractor -->

                    </div>
                </div>
            </div>

        <?php } ?>

    <?php }} ?>

</div>
<!-- End Page Content -->

<div class="bottom-action" >
            
        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.showStatus();">
            <i class="ph-flag font-32"></i>
            <div class="text-center font-14 mt--5">สถานะ</div>
        </div>

        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.showPreview(<?php echo $crmID; ?>)">
            <i class="ph-file-text font-32"></i>
            <div class="text-center font-14 mt--5">รายงาน</div>
        </div>
        
        <div class="flex-1 text-center py-10 cursor-pointer" id="btn-open-comment" onclick="$.showComment(<?php echo $crmID;?>,<?php echo $userID; ?>);">
            <i class="ph-chat-text font-32"></i>
            <div class="text-center font-14 mt--5">คอมเมนต์</div>
        </div>
        
        <div class="flex-1 text-center py-10 cursor-pointer" onclick="location.href='<?php echo site_url('Projects/edit/' . $crmID . '?userid=' . $userID); ?>'">
            <i class="ph-pencil-line font-32"></i>
            <div class="text-center font-14 mt--5">แก้ไข</div>
        </div>

        <div class="flex-1 text-center py-10 cursor-pointer" onclick="location.href='<?php echo site_url('Projects/duplicate/' . $crmID . '?userid=' . $userID); ?>'">
            <i class="ph-copy font-32"></i>
            <div class="text-center font-14 mt--5">ทำซ้ำ</div>
        </div>
        

    
        <div class="flex-1 text-center py-10 cursor-pointer" onclick="$.deleteRecord()">
            <i class="ph-trash font-32"></i>
            <div class="text-center font-14 mt--5">ลบ</div>
        </div>

</div>

<div id="myModal-product-plan" class="modal modal-bottom fade popup-search-modal modal-product" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="flex">
                        <div class="flex-1 pl-5" id="modal-title">
                            Product Plan
                        </div>
                    </div>                    
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18 cursor-pointer product-closed"></i>
                </span>
            </div>
            <div class="modal-body bg-white">
                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order No.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-projectno" id="d-projectno" readonly>
                    </div>

                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order Name</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-projectname" id="d-projectname" readonly>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product No.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-productno" id="d-productno" readonly>
                    </div>

                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product Order Name</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-productname" id="d-productname" readonly>
                    </div>

                </div>

                <div class="card-box mb-10">
                    <form id="form-productplan" method="post" action="" autocomplete="off">
                        <input type="hidden" name="plan-productid" id="plan-productid">
                        <input type="hidden" name="plan-projectid" id="plan-projectid">
                        <input type="hidden" name="plan-lineitem_id" id="plan-lineitem_id">
                        <div class="row">
                            <div class="col-12">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Plan Date <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <div class="base-input-group">
                                    <input type="hidden" id="Planlineitem_id" name="Planlineitem_id">
                                    <input type="text" class="base-input-text datepicker_input datepicker-input" id="product_plan_date" name="product_plan_date" required="" placeholder="DD/MM/YYYY">
                                    <div class="base-input-group-action">
                                        <i class="ph-calendar-blank cursor-pointer" for="product_plan_date"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Plan Qty <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-12">
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
                        <div class="col-4">Plan Qty</div>
                        <div class="col-3"></div>
                    </div>
                </div>

                <div class="list-productplan mb-5" style="height: 200px; overflow:hidden; overflow-y: auto;">
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Plan Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-toatl-qty" id="d-toatl-qty" readonly>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Estimate Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-estimate-qty" id="d-estimate-qty" readonly>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="myModal-product-delivered" class="modal modal-bottom fade popup-search-modal modal-product" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="flex">
                        <div class="flex-1 pl-5" id="modal-title">
                            Product Delivered
                        </div>
                    </div>                    
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18 cursor-pointer product-closed"></i>
                </span>
            </div>
            <div class="modal-body bg-white">
                
                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order No.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-delivered-projectno" id="d-delivered-projectno" readonly>
                    </div>

                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Project Order Name</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-delivered-projectname" id="d-delivered-projectname" readonly>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product No.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-delivered-productno" id="d-delivered-productno" readonly>
                    </div>

                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Product Order Name</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="d-delivered-productname" id="d-delivered-productname" readonly>
                    </div>
                </div>

                <div class="card-box mb-10">
                    <form id="form-product-delivered" method="post" action="" autocomplete="off">
                        <input type="hidden" name="delivered-productid" id="delivered-productid">
                        <input type="hidden" name="delivered-projectid" id="delivered-projectid">
                        <input type="hidden" name="delivered-lineitem_id" id="delivered-lineitem_id">
                        
                        <div class="row mb-5">
                            <div class="col-12">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Dealer Delivered <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'dealerid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~M','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'dealerid1','fieldName'=> 'input-dealerid1','configmodule'=>array(),'count'=>1,'settype'=> 'productinventory'] ); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Delivered Date <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <div class="base-input-group">
                                    <input type="hidden" id="deliveredlineitem_id" name="deliveredlineitem_id">
                                    <input type="text" class="base-input-text datepicker_input datepicker-input" id="product_delivered_date" name="product_delivered_date" required="" placeholder="DD/MM/YYYY">
                                    <div class="base-input-group-action">
                                        <i class="ph-calendar-blank cursor-pointer" for="product_delivered_date"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="pl-5 mb-5">
                                    <span class="label-left">Product Delivered Qty <span style="color: red">*</span></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <input type="text" class="base-input" name="product_delivered_qty" id="product_delivered_qty" required="" onkeypress=" return isNumberPricelist(event);">
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

                <div class="list-productdelivered mb-5" style="height: 300px; overflow:hidden; overflow-y: auto;"><!-- overflow-y: auto; -->
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Delivery Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="toatl-delivery-qty" id="toatl-delivery-qty" readonly>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <label class="pl-5 mb-5">
                            <span class="label-left">Plan Total Qty.</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="base-input base-input-text" name="toatl-plan-qty" id="toatl-plan-qty" readonly>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="myModal-Comment" class="modal modal-bottom fade popup-search-modal modal-product" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="overflow: hidden;">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="flex">
                        <div class="flex-1 pl-5" id="modal-title">
                            Comment
                        </div>
                    </div>                    
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18 cursor-pointer product-closed"></i>
                </span>
            </div>
            <div class="modal-body bg-white" style="overflow-y: auto; overflow-x: hidden;">
                <div class="row" id="list_comment"></div>
            </div>

            <div class="modal-footer">
                <div class="row mb-10">
                    <div class="col-10">
                        <div class="base-input-group bg-white" id="box-comment">
                            <input type="text" class="base-input-text bg-white input-popup-search" id="message-comment" placeholder="Write a comment...">
                        </div>
                    </div>
                    <div class="col-2 text-center" style="padding-right: unset !important; padding-left: unset !important;margin: auto;">
                        <button type="button" class="btn_add_comment" id="btn_add_comment" onclick="$.addComments()">
                            <i class="ph-paper-plane-tilt icon-comment"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="myModal-Status" class="modal modal-bottom fade popup-search-modal modal-product" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="overflow: hidden;">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="flex">
                        <div class="flex-1 pl-5" id="modal-title">
                            Change Status
                        </div>
                    </div>                    
                </div>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ph-x font-18 cursor-pointer product-closed"></i>
                </span>
            </div>

            <div class="modal-body bg-white" style="overflow-y: auto; overflow-x: hidden;">
                <div class="row" id="list_ststus"></div>
            </div>

        </div>
    </div>
</div>

<div id="modal-confirm" class="modal modal-bottom fade" tabindex="-1" role="dialog" data-keyboard="false" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-20">
            <div class="modal-body bg-white">
                <div class="text-center mb-20">
                    <i class="ph-warning-circle text-primary font-70"></i>
                    <div id="change-title">คุณต้องการเปลี่ยนสถานะโครงการใช่หรือไม่ ?</div>
                </div>

                <div class="mb-20" id="row-reason">
                    <input type="hidden" name="change_status" id="change_status">
                </div>

                <div class="mb-5">
                    <button type="button" class="btn btn-custom width-full" id="cancel-change">ไม่ใช่</button>
                    <button type="button" class="btn btn-primary btn-custom width-full" id="confirm-change">ใช่</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .datepicker-dropdown.active{
        z-index: 1060 !important;
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
    var crmID = '<?php echo $crmID; ?>'
    var userID = '<?php echo $userID; ?>'
    var url = '<?php echo $url; ?>'
    var moduleSelect = 'Projects'

    function getPopupListMobileMulti(moduleSelect, fieldID, Count, Settype){
        
        var params = {moduleSelect, offSet}
        
        $.post('<?php echo site_url('Projects/getPopupListWeb'); ?>', params, function(rs){
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
                    $.setPopupValue_WebMulti(fieldID, item, Count, Settype)
                })
                
                $(`#list-${moduleSelect}-${fieldID}`).append(rowItem)
            })
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
        console.log(delivered_date);
        console.log(lineitem);
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

    $(function() {
        setTimeout(function() {
            $('.overlay').hide();
        }, 1000)

        $.closePreview = function() {
            $('#modal-preview').modal('hide');
        }

        $.showPreview = function() {
            window.location.href = '<?php echo site_url('Projects/viewReport/' . $crmID . '?userid=' . $userID . '&action=viewReport'); ?>'
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
                            window.location.href = '<?php echo site_url('Projects/create?userid=' . $this->session->userdata('userID') . '/back'); ?>'
                        } else {
                            Swal.fire('', 'Update Error', 'error')
                        }
                    }, 'json')
                }
            })
        }

        $(".modal-product .product-closed").click(function(i){
            $('.modal-product').modal('hide');
        })

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
            
            $('.overlay').show();
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
                                <div class="col-4 plan-qty-${rs.plan.lineitem_id}">${rs.plan.qty}</div>
                                <div class="col-3">
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
                                <div class="col-4 plan-qty-${rs.plan.lineitem_id}">${rs.plan.qty}</div>
                                <div class="col-3">
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
                                    <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductDelivered('${rs.deliver.lineitem_id}');" role="button"></i>
                                </div>
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
                                    <i class="ph-light ph-trash icon-detail" onclick="$.deletedProductDelivered('${rs.deliver.lineitem_id}');" role="button"></i>
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
                            <div class="col-4 plan-qty-${val.lineitem_id}">${val.qty}</div>
                            <div class="col-3">
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
                $('.overlay').show();
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
                        Swal.fire('', 'ลบรายการสำเร้จ', 'success')
                    } else {
                        $('.overlay').hide();
                        Swal.fire('', rs.message, 'error')
                    }
                },'json')   
                
              }
            });
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
                $('.overlay').show();
                var productid = $('#plan-productid').val()
                var projectid = $('#plan-projectid').val()
                var lineitem = $('#plan-lineitem_id').val()
                
                var productid = $('#delivered-productid').val()
                var projectid = $('#delivered-projectid').val()
                var lineitem = $('#delivered-lineitem_id').val()

                $.post('<?php echo site_url('Projects/delProductDelivered?userid='.$this->session->userdata('userID')); ?>', {crmid:projectid,lineitem_id:lineitem_id,moduleSelect:moduleSelect,productid:productid,lineitem:lineitem,action:'del'}, function(rs){
                    if(rs.status === 'Success'){
                        qty = (rs.qty === null) ? 0 : rs.qty;
                        $('#toatl-delivery-qty').val(qty)
                        calculate_delivery_total(qty,lineitem,rs.delivered_date,rs.clear)
                        $('.delivered-'+lineitem_id).remove();
                        $('.overlay').hide();
                        Swal.fire('', 'ลบรายการสำเร้จ', 'success')
                    } else {
                        $('.overlay').hide();
                        Swal.fire('', rs.message, 'error')
                    }

                },'json')   
                
              }
            });
        
        }

        $.showStatus = function(productid,userid) {
            var params = {moduleSelect, crmID}
            $('.overlay').show();
            $.post('<?php echo site_url('Projects/getStatus'); ?>', params, function(rs){
                $(`#list_ststus`).html('')
                $(rs).each(function( index,value ) {
                    
                    $(value.status).each(function( i,v ) {
                        var sclass = '';
                        var txtonclick = '';
                        if(v.projectorder_status == value.value){
                            sclass = 'selected';
                        }else{
                            txtonclick = `onclick="$.ConfirmStatus('${v.projectorder_status}')"`;
                        }
                        var rowHtml = `<div class="flex width-full px-20" ${txtonclick}>
                                            <div class="flex-1 pl-10 py-15 ${sclass}">
                                                <div class="font-16 text-line-clamp-1">${v.projectorder_status}</div>
                                            </div>
                                        </div>`;
                    $(`#list_ststus`).append(rowHtml)
                    });
                    
                });
            },'json')

            $('#myModal-Status').modal('show').find('.modal-body');
            $('.overlay').hide();
        }
        
        $.ConfirmStatus = function(status) {
            var params = {moduleSelect, crmID}
            $('#myModal-Status').modal('hide');
            $('#change_status').val(status);
            $('#modal-confirm').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        }
        
        $('#cancel-change').click(function() {
            $('#modal-confirm').modal('hide');
            $('#change_status').val('');
        })

        $('#confirm-change').click(function() {
            var status = $('#change_status').val();
            $.setStatus(status)
        })

        $.setStatus = function(status) {
            var crmid = crmID
            var action = 'edit';
            var projectorder_status = status;

            $.post('<?php echo site_url('Projects/updateStatus'); ?>', {
                crmid,
                action,
                projectorder_status
            }, function(rs) {
                $('.overlay').hide();
                if (rs.status) {
                    $('#modal-confirm').modal('hide');
                    $('#change_status').val('');
                    var alertTitle = 'เปลี่ยนแปลงสถานะโครงการสำเร็จ'
                    Swal.fire('', alertTitle, 'success')
                    $('.overlay').show();
                    setTimeout(function() {
                        window.location.reload()
                    }, '1000')
                } else {
                    Swal.fire('', rs.msg, 'error')
                }
            }, 'json')
        }

        $.showComment = function(productid,userid) {
            var params = {moduleSelect, crmID}
            $('.overlay').show();
            
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

            $('#myModal-Comment').modal('show').find('.modal-body');
            $('.overlay').hide();
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

    })
</script>