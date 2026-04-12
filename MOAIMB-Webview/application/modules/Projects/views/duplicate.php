<?php $hide_blocks = array('Owner/Developer Information 1','Owner/Developer Information 2','Owner/Developer Information 3','Owner/Developer Information 4','Consultant Information','Construction Information 1','Construction Information 2','Architecture Information 1','Architecture Information 2','Designer Information 1','Designer Information 2','Designer Information 3','Designer Information 4','Contractor Information 1','Contractor Information 2','Contractor Information 3','Contractor Information 4','Contractor Information 5','Contractor Information 6','Contractor Information 7','Contractor Information 8','Contractor Information 9','Contractor Information 10','Sub Contractor Information 1','Sub Contractor Information 2','Sub Contractor Information 3','Comment Information','Administrator Information'); ?>

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
        <div class="top-nav-back-icon" onclick="Javascript:window.location.href = '<?php echo site_url('Projects/create?userid=' .$this->session->userdata('userID'). '/back'); ?>';"><i class="ph-caret-left"></i></div>
        <span>ข้อมูลโครงการ</span>
        <div class="top-nav-action flex">
            <div class="top-nav-action-icon flex-none cursor-pointer" onclick="$.submitForm()"><i class="ph-floppy-disk"></i></div>
        </div>
    </div>
</div>
<!-- End Top Navbar -->

<!-- Page Content -->
<div class="page-content mt-48">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div data-spy="scroll" data-target="#verticalScrollspy">
                <form id="form-projects" method="post" action="">
                    <input type="hidden" name="crmid" value="<?php echo $crmID; ?>">
                    
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
                                            $field['configmodule'] = $configmodule;
                                            ?>
                                            <?php echo inputGroup($field); ?>
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
                                                        
                                                        <?php if(!empty($itemList['products'])){ $est=0;?>
                                                            <?php foreach ($itemList['products'] as $key => $value) { 

                                                                $p_row = ($key+1);
                                                                $est +=  (int)$value['estimated'];?>
                                                                <tr id="row<?php echo $p_row; ?>" class="row_data">
                                                                    <td class="txt-center txt-middle">
                                                                        
                                                                        <?php if($key==0 && count($itemList['products']) > 1){ ?>
                                                                            <div class="row">
                                                                                <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                    
                                                                                </div>
                                                                                <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                    <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                    <i class="ph-arrow-down show_button_down" title="Down" onclick="PrdUpDown('DOWN','<?php echo $p_row; ?>');"></i>
                                                                                </div>
                                                                            </div>
                                                                        <?php }else if($key > 0 && $p_row < count($itemList['products'])){ ?>
                                                                            <div class="row">
                                                                                <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                    <i class="ph-trash" onclick="PrdRemove('<?php echo $p_row; ?>');"></i>
                                                                                </div>
                                                                                <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                    <i class="ph-arrow-up" title="Up" onclick="PrdUpDown('UP','<?php echo $p_row; ?>');"></i>
                                                                                    <i class="ph-arrow-down show_button_down" title="Down" onclick="PrdUpDown('DOWN','<?php echo $p_row; ?>');"></i>
                                                                                </div>
                                                                            </div>
                                                                        <?php }else if($p_row == count($itemList['products'])){ ?>
                                                                            <div class="row">
                                                                                <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                    <i class="ph-trash" onclick="PrdRemove('<?php echo $p_row; ?>');"></i>
                                                                                </div>
                                                                                <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                    <i class="ph-arrow-up" title="Up" onclick="PrdUpDown('UP','<?php echo $p_row; ?>');"></i>
                                                                                    <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>

                                                                        <input type="hidden" id="deleted<?php echo $p_row; ?>" name="deleted<?php echo $p_row; ?>" value="0">
                                                                    
                                                                    </td>
                                                                        
                                                                    <td>

                                                                        <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'productid'.$p_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['productid'] ,'value_name'=>$value['productname'] ,'module_select'=>'Products','modal' => 'productid1','fieldName'=> 'input-productid'.$p_row,'configmodule'=>$configmodule,'count'=>$p_row,'settype'=> 'productinventory'] ); ?>
                                                                        <div class="mb-2">
                                                                            <textarea class="base-input " id="descriptions<?php echo $p_row; ?>" name="descriptions<?php echo $p_row; ?>" rows="2"><?php echo $value['comment'];?></textarea>
                                                                        </div>

                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="base-input base-input-text" id="product_brand<?php echo $p_row; ?>" name="product_brand<?php echo $p_row; ?>" readonly value="<?php echo $value['product_brand'];?>">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="base-input base-input-text" id="product_group<?php echo $p_row; ?>" name="product_group<?php echo $p_row; ?>" readonly value="<?php echo $value['product_group'];?>">
                                                                    </td>
                                                                    <td>
                                                                        <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'dealerid'.$p_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'dealerid'.$p_row,'fieldName'=> 'input-dealerid'.$p_row,'configmodule'=>$configmodule,'count'=>$p_row,'settype'=> 'productinventory'] ); ?>
                                                                    </td>

                                                                    <td>
                                                                       <div class="base-input-group">
                                                                            <input type="text" class="base-input-text" id="first_delivered_date<?php echo $p_row; ?>" value="" name="first_delivered_date<?php echo $p_row; ?>" readonly placeholder="DD/MM/YYYY">
                                                                            <div class="base-input-group-action">
                                                                                <i class="ph-calendar-blank cursor-pointer" for="first_delivered_date<?php echo $p_row; ?>"></i>
                                                                            </div>
                                                                        </div> 
                                                                    </td>
                                                                    <td>
                                                                        <div class="base-input-group">
                                                                            <input type="text" class="base-input-text" id="last_delivered_date<?php echo $p_row; ?>" value="" name="last_delivered_date<?php echo $p_row; ?>" readonly placeholder="DD/MM/YYYY">
                                                                            <div class="base-input-group-action">
                                                                                <i class="ph-calendar-blank cursor-pointer" for="last_delivered_date<?php echo $p_row; ?>"></i>
                                                                            </div>
                                                                        </div> 
                                                                    </td>
                                                                    <td>
                                                                        <span id="label-estimated1" style="display: none;">Est</span>
                                                                        <input type="text" class="base-input base-input-text" id="estimated<?php echo $p_row; ?>" name="estimated<?php echo $p_row; ?>" value="<?php echo number_format($value['estimated'],0);?>" onkeyup="calcTotal();" onkeypress=" return isNumberPricelist(event);" readonly>
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <input type="text" class="base-input " id="listPrice<?php echo $p_row; ?>" name="listPrice<?php echo $p_row; ?>" value="<?php echo $value['listprice'];?>" onkeypress=" return isNumberPricelist(event);">
                                                                    </td>
                                                                </tr>

                                                            <?php } ?>
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
                                                                        <input type="text" class="base-input base-input-text" name="total_est" id="total_est" value="<?php echo $est;?>" readonly>
                                                                    </td>
                                                                    <td class="pd-10 w-300px border-bottom-radius-5"></td>
                                                                </tr>
                                                            </table>
                                                            <input type="hidden" name="num_products" id="num_products" value="<?php echo $p_row;?>">
                                                        <?php } else{ ?>
                                                                <tr id="row1" class="row_data">
                                                                    <td class="txt-center txt-middle">
                                                                        <input type="hidden" id="deleted1" name="deleted1" value="0">
                                                                    </td>
                                                                        
                                                                    <td>

                                                                        <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'productid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Products','modal' => 'productid1','fieldName'=> 'input-productid1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'productinventory'] ); ?>
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
                                                                        <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'dealerid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'dealerid1','fieldName'=> 'input-dealerid1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'productinventory'] ); ?>
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
                                                                            <input type="text" class="base-input-text" id="last_delivered_date1" value="" name="last_delivered_date1" readonly placeholder="DD/MM/YYYY">
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
                                                                        <input type="text" class="base-input base-input-text" name="total_est" id="total_est" value="0" readonly>
                                                                    </td>
                                                                    <td class="pd-10 w-300px border-bottom-radius-5"></td>
                                                                </tr>
                                                            </table>
                                                            <input type="hidden" name="num_products" id="num_products" value="1">
                                                        <?php }?>

                                                </div>

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
                                                        <?php if(!empty($itemList['competitor'])){?>
                                                            <?php foreach ($itemList['competitor'] as $key => $value) { 
                                                                $com_row = ($key+1); ?>
                                                                    <tr id="row_com<?php echo $com_row; ?>" class="row_data">
                                                                        <td class="txt-center txt-middle">
                                                                            <?php if($key==0 && count($itemList['competitor']) > 1){ ?>
                                                                                <div class="row">
                                                                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                        
                                                                                    </div>
                                                                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                        <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ComUpDown('DOWN','<?php echo $com_row; ?>');"></i>
                                                                                    </div>
                                                                                </div>
                                                                            <?php }else if($key > 0 && $com_row < count($itemList['competitor'])){ ?>
                                                                                <div class="row">
                                                                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                        <i class="ph-trash" onclick="ComRemove('<?php echo $com_row; ?>');"></i>
                                                                                    </div>
                                                                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                        <i class="ph-arrow-up" title="Up" onclick="ComUpDown('UP','<?php echo $com_row; ?>');"></i>
                                                                                        <i class="ph-arrow-down show_button_down" title="Down" onclick="ComUpDown('DOWN','<?php echo $com_row; ?>');"></i>
                                                                                    </div>
                                                                                </div>
                                                                            <?php }else if($com_row == count($itemList['competitor'])){ ?>
                                                                                <div class="row">
                                                                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                        <i class="ph-trash" onclick="ComRemove('<?php echo $com_row; ?>');"></i>
                                                                                    </div>
                                                                                    <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                        <i class="ph-arrow-up" title="Up" onclick="ComUpDown('UP','<?php echo $com_row; ?>');"></i>
                                                                                        <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <input type="hidden" id="deleted_com<?php echo $com_row;?>" name="deleted_com<?php echo $com_row;?>" value="0">
                                                                        </td>
                                                                            
                                                                        <td>
                                                                            <?php echo inputPopupMobileMulti(['uitype' => '', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'competitorproductid'.$com_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['competitorproductid'] ,'value_name'=>$value['competitorproduct_name_th'] ,'module_select'=>'Competitorproduct','modal' => 'competitorproductid'.$com_row,'fieldName'=> 'input-competitorproductid'.$com_row,'configmodule'=>$configmodule,'count'=>$com_row,'settype'=> 'competitorinventory'] ); ?>
                                                                            <div class="mb-2">
                                                                                <textarea class="base-input " id="descriptions_com<?php echo $com_row;?>" name="descriptions_com<?php echo $com_row;?>" rows="2"><?php echo $value['competitorcomment'];?></textarea>
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
                                                                            <input type="text" class="base-input" id="comprtitor_estimated_unit<?php echo $com_row;?>" name="comprtitor_estimated_unit<?php echo $com_row;?>" onkeypress=" return isNumberPricelist(event);" value="<?php echo number_format($value['comprtitor_estimated_unit'],0);?>">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="base-input" id="competitor_price<?php echo $com_row;?>" name="competitor_price<?php echo $com_row;?>" readonly value="<?php echo $value['competitor_price'];?>">
                                                                        </td>
                                                                    </tr>
                                                            <?php } ?>
                                                            <input type="hidden" name="num_compeitor" id="num_compeitor" value="<?php echo $com_row;?>">
                                                        <?php } else{ ?>
                                                            <tr id="row_com1" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_com1" name="deleted_com1" value="0">
                                                            </td>
                                                                
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'competitorproductid1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Competitorproduct','modal' => 'competitorproductid1','fieldName'=> 'input-competitorproductid1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'competitorinventory'] ); ?>
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
                                                            <input type="hidden" name="num_compeitor" id="num_compeitor" value="1">
                                                        <?php } ?>
                                                    </table>

                                                </div>

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
                                                    <?php if(!empty($itemList['designer'])){?>
                                                        <?php foreach ($itemList['designer'] as $key => $value) { 
                                                            $des_row = ($key+1); ?>
                                                            <tr id="row_designer<?php echo $des_row;?>" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                    <?php if(count($itemList['designer']) == 1){ ?>

                                                                    <?php }else if($key==0 && count($itemList['designer']) > 1){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="DesignerUpDown('DOWN','<?php echo $des_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($key > 0 && $des_row < count($itemList['designer'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="DesignerRemove('<?php echo $des_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="DesignerUpDown('UP','<?php echo $des_row; ?>');"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="DesignerUpDown('DOWN','<?php echo $des_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($des_row == count($itemList['designer'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="DesignerRemove('<?php echo $des_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="DesignerUpDown('UP','<?php echo $des_row; ?>');"></i>
                                                                                <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="deleted_designer<?php echo $des_row;?>" name="deleted_designer<?php echo $des_row;?>" value="0">
                                                                </td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'designer'.$des_row, 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'designer'.$des_row,'fieldName'=> 'input-designer'.$des_row,'configmodule'=>$configmodule,'count'=>$des_row,'settype'=> 'designerinventory'] ); ?>
                                                                </td>
                                                                <td><input type="text" class="base-input base-input-text" id="designer_no<?php echo $des_row;?>" name="designer_no<?php echo $des_row;?>" readonly value="<?php echo $value['designer_no'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="designer_name_th<?php echo $des_row;?>" name="designer_name_th<?php echo $des_row;?>" readonly value="<?php echo $value['designer_name_th'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="designer_name_en<?php echo $des_row;?>" name="designer_name_en<?php echo $des_row;?>" readonly value="<?php echo $value['designer_name_en'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="designer_group<?php echo $des_row;?>" name="designer_group<?php echo $des_row;?>" readonly value="<?php echo $value['designer_group'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="designer_industry<?php echo $des_row;?>" name="designer_industry<?php echo $des_row;?>" readonly value="<?php echo $value['designer_industry'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="designer_grade<?php echo $des_row;?>" name="designer_grade<?php echo $des_row;?>" readonly value="<?php echo $value['designer_grade'];?>"></td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_designer'.$des_row, 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['contactid'] ,'value_name'=>$value['contactname'] ,'module_select'=>'Contacts','modal' => 'contact_designer'.$des_row,'fieldName'=> 'input-contact_designer'.$des_row,'configmodule'=>$configmodule,'count'=>$des_row,'settype'=> 'designerinventory'] ); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="service_level_designer<?php echo $des_row;?>" name="service_level_designer<?php echo $des_row;?>" readonly value="<?php echo $value['service_level_designer'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="sales_designer_name<?php echo $des_row;?>" name="sales_designer_name<?php echo $des_row;?>" readonly value="<?php echo $value['sales_designer_name'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input percent_com" id="percen_com_sales_designer<?php echo $des_row;?>" name="percen_com_sales_designer<?php echo $des_row;?>" value="<?php echo $value['percen_com_sales_designer'];?>" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <input type="hidden" name="num_designer" id="num_designer" value="<?php echo $des_row;?>">
                                                    <?php } else{ ?>
                                                        <tr id="row_designer1" class="row_data">
                                                            <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_designer1" name="deleted_designer1" value="0">
                                                            </td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'designer1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'designer1','fieldName'=> 'input-designer1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'designerinventory'] ); ?>
                                                            </td>
                                                            <td><input type="text" class="base-input base-input-text" id="designer_no1" name="designer_no1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="designer_name_th1" name="designer_name_th1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="designer_name_en1" name="designer_name_en1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="designer_group1" name="designer_group1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="designer_industry1" name="designer_industry1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="designer_grade1" name="designer_grade1" readonly value=""></td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_designer1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_designer1','fieldName'=> 'input-contact_designer1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'designerinventory'] ); ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="service_level_designer1" name="service_level_designer1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="sales_designer_name1" name="sales_designer_name1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input percent_com" id="percen_com_sales_designer1" name="percen_com_sales_designer1" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="num_designer" id="num_designer" value="1">
                                                    <?php } ?>
                                                    
                                                    
                                                </table>

                                            </div>
                                            
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
                                                    <?php if(!empty($itemList['architecture'])){?>
                                                        <?php foreach ($itemList['architecture'] as $key => $value) { 
                                                            $arc_row = ($key+1); ?>
                                                            <tr id="row_arc<?php echo $arc_row;?>" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                    <?php if($key==0 && count($itemList['architecture']) > 1){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ArchitectureUpDown('DOWN','<?php echo $arc_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($key > 0 && $arc_row < count($itemList['architecture'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ConsultantRemove('<?php echo $arc_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ArchitectureUpDown('UP','<?php echo $arc_row; ?>');"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ArchitectureUpDown('DOWN','<?php echo $arc_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($arc_row == count($itemList['architecture'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ConsultantRemove('<?php echo $arc_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ArchitectureUpDown('UP','<?php echo $arc_row; ?>');"></i>
                                                                                <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="deleted_architecture<?php echo $arc_row;?>" name="deleted_architecture<?php echo $arc_row;?>" value="0">
                                                                </td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'architecture'.$arc_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'architecture'.$arc_row,'fieldName'=> 'input-architecture'.$arc_row,'configmodule'=>$configmodule,'count'=>$arc_row,'settype'=> 'architectureinventory'] ); ?>
                                                                </td>
                                                                <td><input type="text" class="base-input base-input-text" id="architecture_no<?php echo $arc_row;?>" name="architecture_no<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_no'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="architecture_name_th<?php echo $arc_row;?>" name="architecture_name_th<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_name_th'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="architecture_name_en<?php echo $arc_row;?>" name="architecture_name_en<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_name_en'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="architecture_group<?php echo $arc_row;?>" name="architecture_group<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_group'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="architecture_industry<?php echo $arc_row;?>" name="architecture_industry<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_industry'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="architecture_grade<?php echo $arc_row;?>" name="architecture_grade<?php echo $arc_row;?>" readonly value="<?php echo $value['architecture_grade'];?>"></td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_architecture'.$arc_row, 'readonly' => ' 1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['contactid'] ,'value_name'=>$value['contactname'] ,'module_select'=>'Contacts','modal' => 'contact_architecture'.$arc_row,'fieldName'=> 'input-contact_architecture'.$arc_row,'configmodule'=>$configmodule,'count'=>$arc_row,'settype'=> 'architectureinventory'] ); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="service_level_architecture<?php echo $arc_row;?>" name="service_level_architecture<?php echo $arc_row;?>" readonly value="<?php echo $value['service_level_architecture'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="sales_architecture_name<?php echo $arc_row;?>" name="sales_architecture_name<?php echo $arc_row;?>" readonly value="<?php echo $value['sales_architecture_name'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input percent_com" id="percen_com_sales_architecture<?php echo $arc_row;?>" name="percen_com_sales_architecture<?php echo $arc_row;?>" value="<?php echo $value['percen_com_sales_architecture'];?>" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <input type="hidden" name="num_architecture" id="num_architecture" value="<?php echo $arc_row;?>">
                                                    <?php } else{ ?>
                                                        <tr id="row_arc1" class="row_data">
                                                            <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_architecture1" name="deleted_architecture1" value="0">
                                                            </td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'architecture1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'architecture1','fieldName'=> 'input-architecture1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'architectureinventory'] ); ?>
                                                            </td>
                                                            <td><input type="text" class="base-input base-input-text" id="architecture_no1" name="architecture_no1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="architecture_name_th1" name="architecture_name_th1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="architecture_name_en1" name="architecture_name_en1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="architecture_group1" name="architecture_group1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="architecture_industry1" name="architecture_industry1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="architecture_grade1" name="architecture_grade1" readonly value=""></td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_architecture1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_architecture1','fieldName'=> 'input-contact_architecture1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'architectureinventory'] ); ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="service_level_architecture1" name="service_level_architecture1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="sales_architecture_name1" name="sales_architecture_name1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input percent_com" id="percen_com_sales_architecture1" name="percen_com_sales_architecture1" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="num_architecture" id="num_architecture" value="1">
                                                    <?php } ?> 
                                                </table>

                                            </div>
                                            
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
                                                    <?php if(!empty($itemList['owner'])){?>
                                                        <?php foreach ($itemList['owner'] as $key => $value) { 
                                                            $owner_row = ($key+1); ?>
                                                            <tr id="row_owner<?php echo $owner_row; ?>" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                    <?php if($key==0 && count($itemList['owner']) > 1){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="OwnerUpDown('DOWN','<?php echo $owner_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($key > 0 && $owner_row < count($itemList['owner'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="OwnerRemove('<?php echo $owner_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="OwnerUpDown('UP','<?php echo $owner_row; ?>');"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="OwnerUpDown('DOWN','<?php echo $owner_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($owner_row == count($itemList['owner'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="OwnerRemove('<?php echo $owner_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="OwnerUpDown('UP','<?php echo $owner_row; ?>');"></i>
                                                                                <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="deleted_owner<?php echo $owner_row;?>" name="deleted_owner<?php echo $owner_row;?>" value="0">
                                                                </td>
                                                                    
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'owner'.$owner_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'owner'.$owner_row,'fieldName'=> 'input-owner'.$owner_row,'configmodule'=>$configmodule,'count'=>$owner_row,'settype'=> 'ownerinventory'] ); ?>
                                                                </td>

                                                                <td><input type="text" class="base-input base-input-text" id="owner_no<?php echo $owner_row;?>" name="owner_no<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_no'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="owner_name_th<?php echo $owner_row;?>" name="owner_name_th<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_name_th'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="owner_name_en<?php echo $owner_row;?>" name="owner_name_en<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_name_en'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="owner_group<?php echo $owner_row;?>" name="owner_group<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_group'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="owner_industry<?php echo $owner_row;?>" name="owner_industry<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_industry'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="owner_grade<?php echo $owner_row;?>" name="owner_grade<?php echo $owner_row;?>" readonly value="<?php echo $value['owner_grade'];?>"></td>

                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_owner'.$owner_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['contactid'] ,'value_name'=>$value['contactname'] ,'module_select'=>'Contacts','modal' => 'contact_owner'.$owner_row,'fieldName'=> 'input-contact_owner'.$owner_row,'configmodule'=>$configmodule,'count'=>$owner_row,'settype'=> 'ownerinventory'] ); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="service_level_owner<?php echo $owner_row;?>" name="service_level_owner<?php echo $owner_row;?>" readonly value="<?php echo $value['service_level_owner'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="sales_owner_name<?php echo $owner_row;?>" name="sales_owner_name<?php echo $owner_row;?>" readonly value="<?php echo $value['sales_owner_name'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input percent_com" id="percen_com_sales_owner<?php echo $owner_row;?>" name="percen_com_sales_owner<?php echo $owner_row;?>" value="<?php echo $value['percen_com_sales_owner'];?>" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>

                                                            <input type="hidden" name="num_owner" id="num_owner" value="<?php echo $owner_row;?>">
                                                    <?php } else{ ?>
                                                        <tr id="row_owner1" class="row_data">
                                                            <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_owner1" name="deleted_owner1" value="0">
                                                            </td>
                                                                
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'owner1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'owner1','fieldName'=> 'input-owner1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'ownerinventory'] ); ?>
                                                            </td>
                                                            <td><input type="text" class="base-input base-input-text" id="owner_no1" name="owner_no1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="owner_name_th1" name="owner_name_th1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="owner_name_en1" name="owner_name_en1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="owner_group1" name="owner_group1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="owner_industry1" name="owner_industry1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="owner_grade1" name="owner_grade1" readonly value=""></td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_owner1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_owner1','fieldName'=> 'input-contact_owner1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'ownerinventory'] ); ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="service_level_owner1" name="service_level_owner1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="sales_owner_name1" name="sales_owner_name1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input percent_com" id="percen_com_sales_owner1" name="percen_com_sales_owner1" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="num_owner" id="num_owner" value="1">
                                                    <?php } ?> 
                                                </table>

                                            </div>

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
                                                    <?php if(!empty($itemList['consultant'])){?>
                                                        <?php foreach ($itemList['consultant'] as $key => $value) { 
                                                            $con_row = ($key+1); ?>
                                                            <tr id="row_consul<?php echo $con_row; ?>" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                    <?php if($key==0 && count($itemList['consultant']) > 1){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ConsultantUpDown('DOWN','<?php echo $con_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($key > 0 && $con_row < count($itemList['consultant'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ConsultantRemove('<?php echo $con_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ConsultantUpDown('UP','<?php echo $con_row; ?>');"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ConsultantUpDown('DOWN','<?php echo $con_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($con_row == count($itemList['consultant'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ConsultantRemove('<?php echo $con_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ConsultantUpDown('UP','<?php echo $con_row; ?>');"></i>
                                                                                <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="deleted_consultant<?php echo $con_row; ?>" name="deleted_consultant<?php echo $con_row; ?>" value="0">
                                                                </td>
                                                                    
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'consultant'.$con_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'consultant'.$con_row,'fieldName'=> 'input-consultant'.$con_row,'configmodule'=>$configmodule,'count'=>$con_row,'settype'=> 'consultantinventory'] ); ?>
                                                                </td>

                                                                <td><input type="text" class="base-input base-input-text" id="consultant_no<?php echo $con_row; ?>" name="consultant_no<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_no']; ?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="consultant_name_th<?php echo $con_row; ?>" name="consultant_name_th<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_name_th']; ?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="consultant_name_en<?php echo $con_row; ?>" name="consultant_name_en<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_name_en']; ?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="consultant_group<?php echo $con_row; ?>" name="consultant_group<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_group']; ?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="consultant_industry<?php echo $con_row; ?>" name="consultant_industry<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_industry']; ?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="consultant_grade<?php echo $con_row; ?>" name="consultant_grade<?php echo $con_row; ?>" readonly value="<?php echo $value['consultant_grade']; ?>"></td>

                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_consultant'.$con_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['contactid'] ,'value_name'=>$value['contactname'] ,'module_select'=>'Contacts','modal' => 'contact_consultant'.$con_row,'fieldName'=> 'input-contact_consultant'.$con_row,'configmodule'=>$configmodule,'count'=>$con_row,'settype'=> 'consultantinventory'] ); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="service_level_consultant<?php echo $con_row; ?>" name="service_level_consultant<?php echo $con_row; ?>" readonly value="<?php echo $value['service_level_consultant']; ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="sales_consultant_name<?php echo $con_row; ?>" name="sales_consultant_name<?php echo $con_row; ?>" readonly value="<?php echo $value['sales_consultant_name']; ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input percent_com" id="percen_com_sales_consultant<?php echo $con_row; ?>" name="percen_com_sales_consultant<?php echo $con_row; ?>" value="<?php echo $value['percen_com_sales_consultant']; ?>" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <input type="hidden" name="num_consultant" id="num_consultant" value="<?php echo $con_row;?>">
                                                    <?php } else{ ?>
                                                        <tr id="row_consul1" class="row_data">
                                                            <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_consultant1" name="deleted_consultant1" value="0">
                                                            </td>
                                                                
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'consultant1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'consultant1','fieldName'=> 'input-consultant1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'consultantinventory'] ); ?>
                                                            </td>
                                                            <td><input type="text" class="base-input base-input-text" id="consultant_no1" name="consultant_no1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="consultant_name_th1" name="consultant_name_th1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="consultant_name_en1" name="consultant_name_en1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="consultant_group1" name="consultant_group1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="consultant_industry1" name="consultant_industry1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="consultant_grade1" name="consultant_grade1" readonly value=""></td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_consultant1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_consultant1','fieldName'=> 'input-contact_consultant1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'consultantinventory'] ); ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="service_level_consultant1" name="service_level_consultant1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="sales_consultant_name1" name="sales_consultant_name1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input percent_com" id="percen_com_sales_consultant1" name="percen_com_sales_consultant1" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="num_consultant" id="num_consultant" value="1">
                                                    <?php } ?> 
                                                </table>
                                            </div>

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
                                                    <?php if(!empty($itemList['construction'])){?>
                                                        <?php foreach ($itemList['construction'] as $key => $value) { 
                                                            $cons_row = ($key+1); ?>
                                                            <tr id="row_const<?php echo $cons_row;?>" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                    <?php if(count($itemList['construction']) == 1){ ?>

                                                                    <?php }else if($key==0 && count($itemList['construction']) > 1){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ConstructionUpDown('DOWN','<?php echo $cons_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($key > 0 && $cons_row < count($itemList['construction'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ConstructionRemove('<?php echo $cons_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ConstructionUpDown('UP','<?php echo $cons_row; ?>');"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ConstructionUpDown('DOWN','<?php echo $cons_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($cons_row == count($itemList['construction'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ConstructionRemove('<?php echo $cons_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ConstructionUpDown('UP','<?php echo $cons_row; ?>');"></i>
                                                                                <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="deleted_const<?php echo $cons_row;?>" name="deleted_const<?php echo $cons_row;?>" value="0">
                                                                </td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'construction'.$cons_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'construction'.$cons_row,'fieldName'=> 'input-construction'.$cons_row,'configmodule'=>$configmodule,'count'=>$cons_row,'settype'=> 'constructioninventory'] ); ?>
                                                                </td>
                                                                <td><input type="text" class="base-input base-input-text" id="construction_no<?php echo $cons_row;?>" name="construction_no<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_no'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="construction_name_th<?php echo $cons_row;?>" name="construction_name_th<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_name_th'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="construction_name_en<?php echo $cons_row;?>" name="construction_name_en<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_name_en'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="construction_group<?php echo $cons_row;?>" name="construction_group<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_group'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="construction_industry<?php echo $cons_row;?>" name="construction_industry<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_industry'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="construction_grade<?php echo $cons_row;?>" name="construction_grade<?php echo $cons_row;?>" readonly value="<?php echo $value['construction_grade'];?>"></td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_construction'.$cons_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['contactid'] ,'value_name'=>$value['contactname'] ,'module_select'=>'Contacts','modal' => 'contact_construction'.$cons_row,'fieldName'=> 'input-contact_construction'.$cons_row,'configmodule'=>$configmodule,'count'=>$cons_row,'settype'=> 'constructioninventory'] ); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="service_level_construction<?php echo $cons_row;?>" name="service_level_construction<?php echo $cons_row;?>" readonly value="<?php echo $value['service_level_construction'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="sales_construction_name<?php echo $cons_row;?>" name="sales_construction_name<?php echo $cons_row;?>" readonly value="<?php echo $value['sales_construction_name'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input percent_com" id="percen_com_sales_construction<?php echo $cons_row;?>" name="percen_com_sales_construction<?php echo $cons_row;?>" value="<?php echo $value['percen_com_sales_construction'];?>" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <input type="hidden" name="num_construction" id="num_construction" value="<?php echo $cons_row;?>">
                                                    <?php } else{ ?>
                                                        <tr id="row_const1" class="row_data">
                                                            <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_const1" name="deleted_const1" value="0">
                                                            </td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'construction1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'construction1','fieldName'=> 'input-construction1','configmodule'=>$configmodule,'count'=>$cons_row,'settype'=> 'constructioninventory'] ); ?>
                                                            </td>
                                                            <td><input type="text" class="base-input base-input-text" id="construction_no1" name="construction_no1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="construction_name_th1" name="construction_name_th1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="construction_name_en1" name="construction_name_en1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="construction_group1" name="construction_group1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="construction_industry1" name="construction_industry1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="construction_grade1" name="construction_grade1" readonly value=""></td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_construction1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_construction1','fieldName'=> 'input-contact_construction1','configmodule'=>$configmodule,'count'=>$cons_row,'settype'=> 'constructioninventory'] ); ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="service_level_construction1" name="service_level_construction1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="sales_construction_name1" name="sales_construction_name1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input percent_com" id="percen_com_sales_construction1" name="percen_com_sales_construction1" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="num_construction" id="num_construction" value="1">
                                                    <?php } ?> 
                                                
                                                </table>
                                            </div>
                                            
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
                                                    <?php if(!empty($itemList['contractor'])){?>
                                                        <?php foreach ($itemList['contractor'] as $key => $value) { 
                                                            $cont_row = ($key+1); ?>
                                                            <tr id="row_contractor<?php echo $cont_row;?>" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                    <?php if(count($itemList['contractor']) == 1){ ?>

                                                                    <?php }else if($key==0 && count($itemList['contractor']) > 1){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ContractorUpDown('DOWN','<?php echo $cont_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($key > 0 && $cont_row < count($itemList['contractor'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ContractorRemove('<?php echo $cont_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ContractorUpDown('UP','<?php echo $cont_row; ?>');"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="ContractorUpDown('DOWN','<?php echo $cont_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($cont_row == count($itemList['contractor'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="ContractorRemove('<?php echo $cont_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="ContractorUpDown('UP','<?php echo $cont_row; ?>');"></i>
                                                                                <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="deleted_contractor<?php echo $cont_row;?>" name="deleted_contractor<?php echo $cont_row;?>" value="0">
                                                                </td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contractor'.$cont_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'contractor'.$cont_row,'fieldName'=> 'input-contractor'.$cont_row,'configmodule'=>$configmodule,'count'=>$cont_row,'settype'=> 'contractorinventory'] ); ?>
                                                                </td>
                                                                <td><input type="text" class="base-input base-input-text" id="contractor_no<?php echo $cont_row;?>" name="contractor_no<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_no'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="contractor_name_th<?php echo $cont_row;?>" name="contractor_name_th<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_name_th'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="contractor_name_en<?php echo $cont_row;?>" name="contractor_name_en<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_name_en'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="contractor_group<?php echo $cont_row;?>" name="contractor_group<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_group'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="contractor_industry<?php echo $cont_row;?>" name="contractor_industry<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_industry'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="contractor_grade<?php echo $cont_row;?>" name="contractor_grade<?php echo $cont_row;?>" readonly value="<?php echo $value['contractor_grade'];?>"></td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_contractor'.$cont_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['contactid'] ,'value_name'=>$value['contactname'] ,'module_select'=>'Contacts','modal' => 'contact_contractor'.$cont_row,'fieldName'=> 'input-contact_contractor'.$cont_row,'configmodule'=>$configmodule,'count'=>$cont_row,'settype'=> 'contractorinventory'] ); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="service_level_contractor<?php echo $cont_row;?>" name="service_level_contractor<?php echo $cont_row;?>" readonly value="<?php echo $value['service_level_contractor'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="sales_contractor_name<?php echo $cont_row;?>" name="sales_contractor_name<?php echo $cont_row;?>" readonly value="<?php echo $value['sales_contractor_name'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input percent_com" id="percen_com_sales_contractor<?php echo $cont_row;?>" name="percen_com_sales_contractor<?php echo $cont_row;?>" value="<?php echo $value['percen_com_sales_contractor'];?>" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <input type="hidden" name="num_contractor" id="num_contractor" value="<?php echo $cont_row;?>">
                                                    <?php } else{ ?>
                                                        <tr id="row_contractor1" class="row_data">
                                                            <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_contractor1" name="deleted_contractor1" value="0">
                                                            </td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'contractor1','fieldName'=> 'input-contractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'contractorinventory'] ); ?>
                                                            </td>
                                                            <td><input type="text" class="base-input base-input-text" id="contractor_no1" name="contractor_no1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="contractor_name_th1" name="contractor_name_th1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="contractor_name_en1" name="contractor_name_en1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="contractor_group1" name="contractor_group1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="contractor_industry1" name="contractor_industry1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="contractor_grade1" name="contractor_grade1" readonly value=""></td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_contractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_contractor1','fieldName'=> 'input-contact_contractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'contractorinventory'] ); ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="service_level_contractor1" name="service_level_contractor1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="sales_contractor_name1" name="sales_contractor_name1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input percent_com" id="percen_com_sales_contractor1" name="percen_com_sales_contractor1" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="num_contractor" id="num_contractor" value="1">
                                                    <?php } ?>
                                                    
                                                    
                                                </table>

                                            </div>

                                            
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
                                                    <?php if(!empty($itemList['subcontractor'])){?>
                                                        <?php foreach ($itemList['subcontractor'] as $key => $value) { 
                                                            $subcon_row = ($key+1); ?>
                                                            <tr id="row_subcontractor<?php echo $subcon_row;?>" class="row_data">
                                                                <td class="txt-center txt-middle">
                                                                    <?php if(count($itemList['subcontractor']) == 1){ ?>

                                                                    <?php }else if($key==0 && count($itemList['subcontractor']) > 1){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up disable_button" title="Up"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="SubContractorUpDown('DOWN','<?php echo $subcon_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($key > 0 && $subcon_row < count($itemList['subcontractor'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="SubContractorRemove('<?php echo $subcon_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="SubContractorUpDown('UP','<?php echo $subcon_row; ?>');"></i>
                                                                                <i class="ph-arrow-down show_button_down" title="Down" onclick="SubContractorUpDown('DOWN','<?php echo $subcon_row; ?>');"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php }else if($subcon_row == count($itemList['subcontractor'])){ ?>
                                                                        <div class="row">
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-trash" onclick="SubContractorRemove('<?php echo $subcon_row; ?>');"></i>
                                                                            </div>
                                                                            <div class="col-6" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                                                                <i class="ph-arrow-up" title="Up" onclick="SubContractorUpDown('UP','<?php echo $subcon_row; ?>');"></i>
                                                                                <i class="ph-arrow-down disable_button_down" title="Down"></i>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <input type="hidden" id="deleted_subcontractor<?php echo $subcon_row;?>" name="deleted_subcontractor<?php echo $subcon_row;?>" value="0">
                                                                </td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'subcontractor'.$subcon_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['accountid'] ,'value_name'=>$value['accountname'] ,'module_select'=>'Accounts','modal' => 'subcontractor'.$subcon_row,'fieldName'=> 'input-subcontractor'.$subcon_row,'configmodule'=>$configmodule,'count'=>$subcon_row,'settype'=> 'subcontractorinventory'] ); ?>
                                                                </td>
                                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_no<?php echo $subcon_row;?>" name="sub_contractor_no<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_no'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_th<?php echo $subcon_row;?>" name="sub_contractor_name_th<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_name_th'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_en<?php echo $subcon_row;?>" name="sub_contractor_name_en<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_name_en'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_group<?php echo $subcon_row;?>" name="sub_contractor_group<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_group'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_industry<?php echo $subcon_row;?>" name="sub_contractor_industry<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_industry'];?>"></td>
                                                                <td><input type="text" class="base-input base-input-text" id="sub_contractor_grade<?php echo $subcon_row;?>" name="sub_contractor_grade<?php echo $subcon_row;?>" readonly value="<?php echo $value['sub_contractor_grade'];?>"></td>
                                                                <td>
                                                                    <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_subcontractor'.$subcon_row, 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>$value['contactid'] ,'value_name'=>$value['contactname'] ,'module_select'=>'Contacts','modal' => 'contact_subcontractor'.$subcon_row,'fieldName'=> 'input-contact_subcontractor'.$subcon_row,'configmodule'=>$configmodule,'count'=>$subcon_row,'settype'=> 'subcontractorinventory'] ); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="service_level_sub_contractor<?php echo $subcon_row;?>" name="service_level_sub_contractor<?php echo $subcon_row;?>" readonly value="<?php echo $value['service_level_sub_contractor'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input base-input-text" id="sales_sub_contractor_name<?php echo $subcon_row;?>" name="sales_sub_contractor_name<?php echo $subcon_row;?>" readonly value="<?php echo $value['sales_sub_contractor_name'];?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="base-input percent_com" id="percen_com_sales_sub_contractor<?php echo $subcon_row;?>" name="percen_com_sales_sub_contractor<?php echo $subcon_row;?>" value="<?php echo $value['percen_com_sales_sub_contractor'];?>" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <input type="hidden" name="num_subcontractor" id="num_subcontractor" value="<?php echo $subcon_row;?>">
                                                    <?php } else{ ?>
                                                        <tr id="row_subcontractor1" class="row_data">
                                                            <td class="txt-center txt-middle">
                                                                <input type="hidden" id="deleted_subcontractor1" name="deleted_subcontractor1" value="0">
                                                            </td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'subcontractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'subcontractor1','fieldName'=> 'input-subcontractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'subcontractorinventory'] ); ?>
                                                            </td>
                                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_no1" name="sub_contractor_no1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_th1" name="sub_contractor_name_th1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_name_en1" name="sub_contractor_name_en1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_group1" name="sub_contractor_group1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_industry1" name="sub_contractor_industry1" readonly value=""></td>
                                                            <td><input type="text" class="base-input base-input-text" id="sub_contractor_grade1" name="sub_contractor_grade1" readonly value=""></td>
                                                            <td>
                                                                <?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_subcontractor1', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_subcontractor1','fieldName'=> 'input-contact_subcontractor1','configmodule'=>$configmodule,'count'=>1,'settype'=> 'subcontractorinventory'] ); ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="service_level_sub_contractor1" name="service_level_sub_contractor1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input base-input-text" id="sales_sub_contractor_name1" name="sales_sub_contractor_name1" readonly value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="base-input percent_com" id="percen_com_sales_sub_contractor1" name="percen_com_sales_sub_contractor1" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">
                                                            </td>
                                                        </tr>
                                                        <input type="hidden" name="num_subcontractor" id="num_subcontractor" value="1">
                                                    <?php } ?>
                                                </table>
                                            </div>

                                            <button type="button" class="btn_add_subcontractor" id="add_subcontractor">
                                                <img src="<?php echo base_url('assets/img/icon/plus.png'); ?>">&nbsp;Add New Landscape
                                            </button>
                                        </div>
                                        <!-- Sub Contractor -->

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
            
            <button type="submit" class="btn btn-primary btn-save-form" onclick="$.submitForm()"><i class="ph-bold ph-floppy-disk" style="font-size: 18px;vertical-align: inherit;"></i>&nbsp;Save</button>
            
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
        
        if(filter === undefined) {
            $(`#${fieldID}-modal-search-box`).val('')
            $(`#${fieldID}-modal-search-hidden`).val('')
            $(`#${fieldID}-modal-select-hidden`).val('')
        }

        if(relate_field_up !== undefined && relate_field_up !== ''){
            params.relate_field_up = $(`#${relate_field_up}`).val()
            field_up = relate_field_up
        }
        
        if(relate_field_down !== undefined && relate_field_down !== ''){
            var field_down = relate_field_down
        }

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
                    $.setPopupValue_Web(fieldID, item, field_up, field_down)
                })
                
                $(`#list-${moduleSelect}-${fieldID}`).append(rowItem)
            })
        },'json')
    }
    
    function getPopupListMobileMulti(moduleSelect, fieldID, Count, Settype){
        
        var params = {moduleSelect, offSet}
        //if(filter !== undefined) params.filter = filter

        if(moduleSelect === 'Contacts'){
            var accountField = fieldID.replace('contact_', '')
            var accountID = $(`#${accountField}`).val()
            params = {
                ...params,
                ...{ relate_field_up: accountID }
            }
        }

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
                console.log(estimated);
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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'productid${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Products','modal' => 'productid${count}','fieldName'=> 'input-productid${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'productinventory'] ); ?>
                <div class="mb-2">
                    <textarea class="base-input " id="descriptions${count}" name="descriptions${count}" rows="2"></textarea>
                </div>`

            colthree.innerHTML=`<input type="text" class="base-input base-input-text" id="product_brand${count}" name="product_brand${count}" readonly value="">`;

            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="product_group${count}" name="product_group${count}" readonly value="">`;

            colfive.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'dealerid${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'dealerid${count}','fieldName'=> 'input-dealerid${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'productinventory'] ); ?>`;

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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'competitorproductid${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Competitorproduct','modal' => 'competitorproductid${count}','fieldName'=> 'input-competitorproductid${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'competitorinventory'] ); ?>
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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'owner${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'owner${count}','fieldName'=> 'input-owner${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'ownerinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_no${count}" name="owner_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_name_th${count}" name="owner_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_name_en${count}" name="owner_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_group${count}" name="owner_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_industry${count}" name="owner_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="owner_grade${count}" name="owner_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_owner${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_owner${count}','fieldName'=> 'input-contact_owner${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'ownerinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_owner${count}" name="service_level_owner${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_owner_name${count}" name="sales_owner_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_owner${count}" name="percen_com_sales_owner${count}" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">`;

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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'consultant${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'consultant${count}','fieldName'=> 'input-consultant${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'consultantinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_no${count}" name="consultant_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_name_th${count}" name="consultant_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_name_en${count}" name="consultant_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_group${count}" name="consultant_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_industry${count}" name="consultant_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="consultant_grade${count}" name="consultant_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_consultant${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_consultant${count}','fieldName'=> 'input-contact_consultant${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'consultantinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_consultant${count}" name="service_level_consultant${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_consultant_name${count}" name="sales_consultant_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_consultant${count}" name="percen_com_sales_consultant${count}" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">`;

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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'architecture${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'architecture${count}','fieldName'=> 'input-architecture${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'architectureinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_no${count}" name="architecture_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_name_th${count}" name="architecture_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_name_en${count}" name="architecture_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_group${count}" name="architecture_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_industry${count}" name="architecture_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="architecture_grade${count}" name="architecture_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_architecture${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_architecture${count}','fieldName'=> 'input-contact_architecture${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'architectureinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_architecture${count}" name="service_level_architecture${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_architecture_name${count}" name="sales_architecture_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_architecture${count}" name="percen_com_sales_architecture${count}" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">`;

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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'construction${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'construction${count}','fieldName'=> 'input-construction${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'constructioninventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_no${count}" name="construction_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_name_th${count}" name="construction_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_name_en${count}" name="construction_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_group${count}" name="construction_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_industry${count}" name="construction_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="construction_grade${count}" name="construction_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_construction${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_construction${count}','fieldName'=> 'input-contact_construction${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'constructioninventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_construction${count}" name="service_level_construction${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_construction_name${count}" name="sales_construction_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_construction${count}" name="percen_com_sales_construction${count}" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">`;

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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'designer${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'designer${count}','fieldName'=> 'input-designer${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'designerinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_no${count}" name="designer_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_name_th${count}" name="designer_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_name_en${count}" name="designer_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_group${count}" name="designer_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_industry${count}" name="designer_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="designer_grade${count}" name="designer_grade${count}" readonly value="">`
            
            colnine.innerHTML=`<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_designer${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_designer${count}','fieldName'=> 'input-contact_designer${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'designerinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_designer${count}" name="service_level_designer${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_designer_name${count}" name="sales_designer_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_designer${count}" name="percen_com_sales_designer${count}" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">`;

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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'contractor${count}','fieldName'=> 'input-contractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'contractorinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_no${count}" name="contractor_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_name_th${count}" name="contractor_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_name_en${count}" name="contractor_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_group${count}" name="contractor_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_industry${count}" name="contractor_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="contractor_grade${count}" name="contractor_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_contractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_contractor${count}','fieldName'=> 'input-contact_contractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'contractorinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_contractor${count}" name="service_level_contractor${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_contractor_name${count}" name="sales_contractor_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_contractor${count}" name="percen_com_sales_contractor${count}" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">`;

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

            coltwo.innerHTML = `<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'subcontractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Accounts','modal' => 'subcontractor${count}','fieldName'=> 'input-subcontractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'subcontractorinventory'] ); ?>`;

            colthree.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_no${count}" name="sub_contractor_no${count}" readonly value="">`
            colfour.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_name_th${count}" name="sub_contractor_name_th${count}" readonly value="">`
            colfive.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_name_en${count}" name="sub_contractor_name_en${count}" readonly value="">`
            colsix.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_group${count}" name="sub_contractor_group${count}" readonly value="">`
            colseven.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_industry${count}" name="sub_contractor_industry${count}" readonly value="">`
            coleight.innerHTML = `<input type="text" class="base-input base-input-text" id="sub_contractor_grade${count}" name="sub_contractor_grade${count}" readonly value="">`

            colnine.innerHTML=`<?php echo inputPopupMobileMulti(['uitype' => '1000', 'fieldClass' => '', 'fieldlabel' => '','columnname' => 'contact_subcontractor${count}', 'value' => '', 'readonly' => '1', 'rows' => '', 'typeofdata' => 'V~O','value'=>'' ,'value_name'=>'' ,'module_select'=>'Contacts','modal' => 'contact_subcontractor${count}','fieldName'=> 'input-contact_subcontractor${count}','configmodule'=>$configmodule,'count'=>'${count}','settype'=> 'subcontractorinventory'] ); ?>`;

            colten.innerHTML = `<input type="text" class="base-input base-input-text" id="service_level_sub_contractor${count}" name="service_level_sub_contractor${count}" readonly value="">`;

            coleleven.innerHTML = `<input type="text" class="base-input base-input-text" id="sales_sub_contractor_name${count}" name="sales_sub_contractor_name${count}" readonly value="">`;

            coltwelve.innerHTML = `<input type="text" class="base-input percent_com" id="percen_com_sales_sub_contractor${count}" name="percen_com_sales_sub_contractor${count}" value="" onkeyup="validTotalCom();" onkeypress=" return isNumberPricelist(event);">`;

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

        /*$('.input-popup-search').keyup(function(i, e){
            var len = $(this).val().length
            var moduleSelect = $(this).data('moduleselect')
            var fieldID = $(this).data('field')
            
            if(len === 0 || len > 2){
                offSet = 0
                getPopupList(moduleSelect, fieldID, $(this).val())
            }
        })*/

        $('.input-popup-search').keyup(function(i, e){
            var len = $(this).val().length
            var moduleSelect = $(this).data('moduleselect')
            var fieldID = $(this).data('field')
            
            var relate_field_down = $(this).data('relate-field-down')
            var relate_field_up = $(this).data('relate-field-up')
            
            if(len === 0 || len > 2){
                offSet = 0
                getPopupList(moduleSelect, fieldID, $(this).val(), '', '1', relate_field_up, relate_field_down)
            }
        })
        
        $.submitForm = function(){
            $('#form-projects').submit();
        }

        $('#form-projects').submit(function(e) {

            e.preventDefault();

            var validCom = validTotalCom();
            if(!validCom) return false
            
            var form = $(this);
            form.find(':input').prop('disabled', false);
            var formData = form.serializeObject()
            var isCustomerInfo = false
            for (var key in formData){
                var required = $(`#${key}`).prop('required')
                var fieldLabel = $(`#label-${key}`).html()
                var fieldValue = formData[key]

                $(`#${key}`).removeClass('input-error')
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

            formData.action = 'duplicate'
            $('.overlay').show();
            $.post('<?php echo site_url('Projects/save'); ?>', formData, function(rs){
                if(rs.status === 'Success'){
                    window.location.href = `<?php echo site_url('Projects/view'); ?>/${rs.data.Crmid}?userid=${rs.userID}`
                } else {
                    $('.overlay').hide();
                    Swal.fire('', rs.message, 'error')
                }
            },'json')
        })
    })
</script>