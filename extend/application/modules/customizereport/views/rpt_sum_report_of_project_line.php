<?php global $site_URL; ?>
<div style="height: 780px;">
    <form id="dataForm" class="easyui-form" method="POST" name="dataForm" style="height: 100%">
        <div class="row">
            <div class="col-lg-12">
                <div id="layout" class="easyui-layout" style="width:100%;height:100%;">

                    <div class="row">
                        <div class="col-lg-9">
                            <div class="row panel-body layout-body" style="height:100%; padding-top:15px;">
                               <!-- //box1 -->
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Order No.</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox projectorder_no" id="projectorder_no"
                                        name="projectorder_no" style="width:100%;height:25px;" />
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox projectorder_name " id="projectorder_name"
                                        name="projectorder_name" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Type</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox project_type" data-options="required:true" id="project_type"
                                        name="project_type" style="width:100%; height:25px;" />
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Size</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox project_size" data-options="required:true" id="project_size"
                                        name="project_size" style="width:100%; height:25px;" />
                                </div>
                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Opportunity</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox project_opportunity" data-options="required:true" id="project_opportunity"
                                        name="project_opportunity" style="width:100%; height:25px;" />
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Sales Rep.</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox sale_rap" data-options="required:true" id="sale_rap"
                                        name="sale_rap" style="width:100%; height:25px;" />
                                </div>
                                <!-- //box1 -->

                                <!-- //box2 -->
                                <div style="clear:both; height:20px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Project Open Date</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="project_open_date_from" name="project_open_date_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="project_open_date_to" name="project_open_date_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Delivery from Date</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="delivery_from_date" name="delivery_from_date" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="delivery_to_date" name="delivery_to_date" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Project Est. Qty (Sum Est.)</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_est_from" name="total_est_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_est_to" name="total_est_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Project Plan Qty (Sum Plan)</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_plan_from" name="total_plan_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_plan_to" name="total_plan_to" value=""  style="height:25pt;" />
                                </div>

                                 <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Project On Hand Qty (Sum On Hand)</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_on_hand_from" name="total_on_hand_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_on_hand_to" name="total_on_hand_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Project Delivered Qty (Sum Delivered)</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_deli_from" name="total_deli_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="total_deli_to" name="total_deli_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Product Code</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox product_code_crm" id="product_code_crm"
                                        name="product_code_crm" style="width:100%;height:25px;" />
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Product Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox productname " id="productname"
                                        name="productname" style="width:100%;height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Product Brand</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox product_brand" id="product_brand"
                                        name="product_brand" style="width:100%;height:25px;" />
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Dealer</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="dealer_id" id="dealer_id" type="hidden">  
                                    <input id="dealer_name" class="easyui-textbox dealer_name" style="width:100%;">
                                </div>

                                <!-- // -->
                                 <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Product Comment</label>
                                </div>
                                <div class="col-xs-9">
                                    <input class="easyui-textbox product_remark" id="product_remark"
                                        name="product_remark" style="width:100%;height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">First Delivered Date</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="first_delivered_date_from" name="first_delivered_date_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="first_delivered_date_to" name="first_delivered_date_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Last Delivered Date</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="last_delivered_date_from" name="last_delivered_date_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="last_delivered_date_to" name="last_delivered_date_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Est. Qty</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="estimated_from" name="estimated_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="estimated_to" name="estimated_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Plan Qty</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="plan_from" name="plan_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="plan_to" name="plan_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">On Hand Qty</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="remain_on_hand_from" name="remain_on_hand_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="remain_on_hand_to" name="remain_on_hand_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Delivered Qty</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="delivered_from" name="delivered_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="delivered_to" name="delivered_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Selling Price</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="selling_price_from" name="selling_price_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-numberbox" data-options="precision:2" id="selling_price_to" name="selling_price_to" value=""  style="height:25pt;" />
                                </div>

                                <!-- // -->
                                <span style="display:none;">
                                    <div style="clear:both; height:8px; "></div>
                                    <div class="col-xs-3" style="text-align:right;">
                                        <label for="menu">Product Delivery Date</label>
                                    </div>
                                    <div class="col-xs-2">
                                        <input type="text" class="easyui-datebox" id="product_delivered_date_from" name="product_delivered_date_from" value=""  style="height:25pt;" />
                                    </div>
                                    <div class="col-xs-1" style="text-align:center;">
                                        <label for="menu">ถึง</label>
                                    </div>
                                    <div class="col-xs-2">
                                        <input type="text" class="easyui-datebox" id="product_delivered_date_to" name="product_delivered_date_to" value=""  style="height:25pt;" />
                                    </div>

                                    <!-- // -->
                                    <div style="clear:both; height:8px; "></div>
                                    <div class="col-xs-3" style="text-align:right;">
                                        <label for="menu">Product Delivery Qty</label>
                                    </div>
                                    <div class="col-xs-2">
                                        <input type="text" class="easyui-numberbox" data-options="precision:2" id="product_delivered_qty_from" name="product_delivered_qty_from" value=""  style="height:25pt;" />
                                    </div>
                                    <div class="col-xs-1" style="text-align:center;">
                                        <label for="menu">ถึง</label>
                                    </div>
                                    <div class="col-xs-2">
                                        <input type="text" class="easyui-numberbox" data-options="precision:2" id="product_delivered_qty_to" name="product_delivered_qty_to" value=""  style="height:25pt;" />
                                    </div>
                                </span>
                                <!-- //box2 -->

                                <!-- //box3 -->
                                <div style="clear:both; height:20px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Account Name (Related)</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="account_id" id="account_id" type="hidden">  
                                    <input id="account_name" class="easyui-textbox account_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Sales Rep. Code (Related)</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="sale_rap_code_related" name="sale_rap_code_related" style="width:100%; height:25px;" />
                                </div>
                                <!-- //box3 -->

                                <!-- //box4 -->
                                <div style="clear:both; height:20px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Owner Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="owner_id" id="owner_id" type="hidden">  
                                    <input id="owner_name" class="easyui-textbox owner_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Owner Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="owner_sales_rap" name="owner_sales_rap" style="width:100%; height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Consultant Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="consultant_id" id="consultant_id" type="hidden">  
                                    <input id="consultant_name" class="easyui-textbox consultant_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Consultant Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="consultant_sales_rap" name="consultant_sales_rap" style="width:100%; height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Architect Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="architect_id" id="architect_id" type="hidden">  
                                    <input id="architect_name" class="easyui-textbox architect_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Architect Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="architect_sales_rap" name="architect_sales_rap" style="width:100%; height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Construction Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="construction_id" id="construction_id" type="hidden">  
                                    <input id="construction_name" class="easyui-textbox construction_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Construction Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="construction_sales_rap" name="construction_sales_rap" style="width:100%; height:25px;" />
                                </div>

                                 <!-- // -->
                                 <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Interior Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="interior_id" id="interior_id" type="hidden">  
                                    <input id="interior_name" class="easyui-textbox interior_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Interior Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="interior_sales_rap" name="interior_sales_rap" style="width:100%; height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Main Cont. Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="maincont_id" id="maincont_id" type="hidden">  
                                    <input id="maincont_name" class="easyui-textbox maincont_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Main Cont. Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="maincont_sales_rap" name="maincont_sales_rap" style="width:100%; height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Sub Contractor Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="subcontractor_id" id="subcontractor_id" type="hidden">  
                                    <input id="subcontractor_name" class="easyui-textbox subcontractor_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Sub Contractor Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="subcontractor_sales_rap" name="subcontractor_sales_rap" style="width:100%; height:25px;" />
                                </div>

                                <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Dealer Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="dealer_id2" id="dealer_id2" type="hidden">  
                                    <input id="dealer_name2" class="easyui-textbox dealer_name2" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Dealer Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="dealer_sales_rap" name="dealer_sales_rap" style="width:100%; height:25px;" />
                                </div>
                                <div style="clear:both; height:8px; "></div>
                                <!-- //box4 -->

                                <!-- // -->
                                <span style="display:none;">
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Sub Dealer Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="sup_dealer_id" id="sup_dealer_id" type="hidden">  
                                    <input id="sup_dealer_name" class="easyui-textbox sup_dealer_name" style="width:100%;">
                                </div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Sup Dealer Sales Rep. Name</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox sale" data-options="required:true" id="sup_dealer_sales_rap" name="sup_dealer_sales_rap" style="width:100%; height:25px;" />
                                </div>
                                <div style="clear:both; height:8px; "></div>
                                </span>
                                <!-- //box5 -->


                            </div>

                            <input type="hidden" name="date_from" id="date_from" value="">
                            <input type="hidden" name="date_to" id="date_to" value="">
                        </div>
                        <!-- col-lg-9 -->
                        <div class="col-lg-3">
                            <div class="row panel-body layout-body" style="height: 120px; padding-top:15px;">
                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Filter_report()"
                                        style="width:120px;"><i class="fa fa-search"></i>
                                        Search</a>
                                </div>

                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="rpt_sum_report_of_project_line()"
                                        style="width:120px;"><img src="../../../themes/softed/images/actionGenerateExcel.png" hspace="5"
                                            align="absmiddle" border="0" style="width: 18px;">
                                        Export Excel</a>
                                </div>

                                <div style="clear:both; height:8px; "></div>
                                <!-- <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Export_report_pdf()"
                                        style="width:120px;"><img src="../../../themes/softed/images/pdf.png" hspace="5"
                                            align="absmiddle" border="0" style="width: 18px;">
                                        Export PDF</a>
                                </div> -->
                                <!-- <div style="clear:both; height:8px; "></div>

                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Send_report()"
                                        style="width:120px;"><i class="fa fa-envelope"></i>
                                        Send Report</a>
                                </div> -->
                            </div>

                        </div>
                        <!-- col-lg-3 -->
                        <div class="col-lg-12" style="text-align:center">
                            <div data-options="region:'center',title:'',iconCls:'icon-ok'" id="report-result"
                                style="width:100%; height:540px; margin-top:70px;">
                                <iframe id="birt-result" style="padding:0 0 0 0; min-height:500px;" frameborder="0"
                                    width="100%" height="auto">
                                </iframe>
                            </div>
                        </div>
                    </div>


                </div>
                <!--region-->



            </div>
            <!--layout-->
        </div>
        <!--co-lg-12-->

    </form>
</div>



<!--Open Send Report -->
<div id="dialog" title="Send Report" style="width:850px;height:600px; margin-top:10px;">

</div>
<!--Close Send Report -->

<style type="text/css">
.content {
    height: 800px !important;
}
</style>
<script>
$(document).ready(function() {

    Getsendtofromuser_sendto('<?php echo USERID ?>');
    Getmonthly_Report();
    GetProjectType();
    GetProjectSize();
    GetProjectOpportunity();
    GetSaleRap();
    GetProductBrand();

    /* on load ref file assets/js/utilities.js */
    $("#dialog").css("display", "none");

    $('.row').resize(function() {
        $('#dg').datagrid('resize');
        $('#layout').layout('resize');
    });
});

function checkvalid() {
    // if ($("#monthly").combogrid('getValue') == '') {
    //     $('#monthly').next().find('input').focus();
    //     return false;
    // } else {
    //     return true;
    // }
    return true;
}

function checkval_send() {
    if ($("#send_to").combogrid('getValues') == '') {
        $('#send_to').next().find('input').focus();
        return false;
        // }else if ($("#send_cc").combogrid('getValues') == '') {
        //     $('#send_cc').next().find('input').focus();
        //     return false;
    } else if ($("#monthly").combogrid('getValue') == '') {
        $('#monthly').next().find('input').focus();
        return false;
    } else {
        return true;
    }
}

function Filter_report() {
    var projectorder_no = $("#projectorder_no").val();
    var projectorder_name = $("#projectorder_name").val();
    var project_type =  $("#project_type").combogrid('getValue');
    var project_size =  $("#project_size").combogrid('getValue');
    var project_opportunity =  $("#project_opportunity").combogrid('getValue');
    var sale_rap =  $("#sale_rap").combogrid('getValue');

    var project_open_date_from = $("#project_open_date_from").datebox('getValue');
    if(project_open_date_from != ''){
        var project_open_date_from_split = project_open_date_from.split('/');
        project_open_date_from = project_open_date_from_split[2] + '-' + project_open_date_from_split[1] + '-' + project_open_date_from_split[0];
    }

    var project_open_date_to = $("#project_open_date_to").datebox('getValue');
    if(project_open_date_to != ''){
        var project_open_date_to_split = project_open_date_to.split('/');
        project_open_date_to = project_open_date_to_split[2] + '-' + project_open_date_to_split[1] + '-' + project_open_date_to_split[0];
    }

    var delivery_from_date = $("#delivery_from_date").datebox('getValue');
    if(delivery_from_date != ''){
        var delivery_from_date_split = delivery_from_date.split('/');
        delivery_from_date = delivery_from_date_split[2] + '-' + delivery_from_date_split[1] + '-' + delivery_from_date_split[0];
    }

    var delivery_to_date = $("#delivery_to_date").datebox('getValue');
    if(delivery_to_date != ''){
        var delivery_to_date_split = delivery_to_date.split('/');
        delivery_to_date = delivery_to_date_split[2] + '-' + delivery_to_date_split[1] + '-' + delivery_to_date_split[0];
    }

    var total_est_from = $("#total_est_from").val();
    var total_est_to = $("#total_est_to").val();

    var total_plan_from = $("#total_plan_from").val();
    var total_plan_to = $("#total_plan_to").val();

    var total_on_hand_from = $("#total_on_hand_from").val();
    var total_on_hand_to = $("#total_on_hand_to").val();

    var total_deli_from = $("#total_deli_from").val();
    var total_deli_to = $("#total_deli_to").val();

    var product_code_crm = $("#product_code_crm").val();
    var productname = $("#productname").val();

    var product_brand =  $("#product_brand").combogrid('getValue');
    var dealer_id =  $("#dealer_id").val();

    var product_remark = $("#product_remark").val();
    
    var first_delivered_date_from = $("#first_delivered_date_from").datebox('getValue');
    if(first_delivered_date_from != ''){
    var first_delivered_date_from_split = first_delivered_date_from.split('/');
        first_delivered_date_from = first_delivered_date_from_split[2] + '-' + first_delivered_date_from_split[1] + '-' + first_delivered_date_from_split[0];
    }

    var first_delivered_date_to = $("#first_delivered_date_to").datebox('getValue');
    if(first_delivered_date_to != ''){
        var first_delivered_date_to_split = first_delivered_date_to.split('/');
        first_delivered_date_to = first_delivered_date_to_split[2] + '-' + first_delivered_date_to_split[1] + '-' + first_delivered_date_to_split[0];
    }

    var last_delivered_date_from = $("#last_delivered_date_from").datebox('getValue');
    if(last_delivered_date_from != ''){
        var last_delivered_date_from_split = last_delivered_date_from.split('/');
        last_delivered_date_from = last_delivered_date_from_split[2] + '-' + last_delivered_date_from_split[1] + '-' + last_delivered_date_from_split[0];
    }

    var last_delivered_date_to = $("#last_delivered_date_to").datebox('getValue');
    if(last_delivered_date_to != ''){
        var last_delivered_date_to_split = last_delivered_date_to.split('/');
        last_delivered_date_to = last_delivered_date_to_split[2] + '-' + last_delivered_date_to_split[1] + '-' + last_delivered_date_to_split[0]; 
    }
        
    var estimated_from = $("#estimated_from").val();
    var estimated_to = $("#estimated_to").val(); 

    var plan_from = $("#plan_from").val(); 
    var plan_to = $("#plan_to").val();

    var remain_on_hand_from = $("#remain_on_hand_from").val(); 
    var remain_on_hand_to = $("#remain_on_hand_to").val();

    var delivered_from = $("#delivered_from").val(); 
    var delivered_to = $("#delivered_to").val(); 

    var selling_price_from = $("#selling_price_from").val(); 
    var selling_price_to = $("#selling_price_to").val();
    
    var product_delivered_date_from = $("#product_delivered_date_from").datebox('getValue');
    if(product_delivered_date_from != ''){
        var product_delivered_date_from_split = product_delivered_date_from.split('/');
        product_delivered_date_from = product_delivered_date_from_split[2] + '-' + product_delivered_date_from_split[1] + '-' + product_delivered_date_from_split[0];
    }

    var product_delivered_date_to = $("#product_delivered_date_to").datebox('getValue');
    if(product_delivered_date_to != ''){
        var product_delivered_date_to_split = product_delivered_date_to.split('/');
        product_delivered_date_to = product_delivered_date_to_split[2] + '-' + product_delivered_date_to_split[1] + '-' + product_delivered_date_to_split[0];
    }

    var product_delivered_qty_from = $("#product_delivered_qty_from").val(); 
    var product_delivered_qty_to = $("#product_delivered_qty_to").val();

    var account_id =  $("#account_id").val();
    var sale_rap_code_related =  $("#sale_rap_code_related").combogrid('getValue');
    
    var owner_id = $("#owner_id").val();
    var owner_sales_rap = $("#owner_sales_rap").combogrid('getValue');

    var consultant_id =  $("#consultant_id").val();
    var consultant_sales_rap = $("#consultant_sales_rap").combogrid('getValue');

    var architect_id = $("#architect_id").val();
    var architect_sales_rap = $("#architect_sales_rap").combogrid('getValue');

    var construction_id = $("#construction_id").val();
    var construction_sales_rap = $("#construction_sales_rap").combogrid('getValue');

    var interior_id =  $("#interior_id").val();
    var interior_sales_rap = $("#interior_sales_rap").combogrid('getValue');

    var maincont_id = $("#maincont_id").val();
    var maincont_sales_rap = $("#maincont_sales_rap").combogrid('getValue');

    var subcontractor_id = $("#subcontractor_id").val();
    var subcontractor_sales_rap = $("#subcontractor_sales_rap").combogrid('getValue');

    var dealer_id2 = $("#dealer_id2").val();
    var dealer_sales_rap = $("#dealer_sales_rap").combogrid('getValue');

    var sup_dealer_id = $("#sup_dealer_id").val();
    var sup_dealer_sales_rap = $("#sup_dealer_sales_rap").combogrid('getValue');

    // console.log("account_id=",account_id)
    // console.log("sale_rap_code_related=",sale_rap_code_related);

    var form_data = $('#dataForm').serialize();
    var returnvalid = checkvalid();
    if (returnvalid === false) {
        return false;
    }
    $.messager.progress();

    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_sum_report_of_project_line_excel.rptdesign&__showtitle=false'
    +'&s1='+projectorder_no
 +'&s2='+projectorder_name
 +'&s3='+project_type
 +'&s4='+project_size
 +'&s5='+project_opportunity
 +'&s6='+sale_rap
 +'&s7='+project_open_date_from
 +'&s8='+project_open_date_to
 +'&s9='+delivery_from_date
 +'&s10='+delivery_to_date
 +'&s11='+total_est_from
 +'&s12='+total_est_to
 +'&s13='+total_plan_from
 +'&s14='+total_plan_to
 +'&s15='+total_on_hand_from
 +'&s16='+total_on_hand_to
 +'&s17='+total_deli_from
 +'&s18='+total_deli_to
 +'&s19='+product_code_crm
 +'&s20='+productname
 +'&s21='+product_brand
 +'&s22='+dealer_id
 +'&s23='+product_remark
 +'&s24='+first_delivered_date_from
 +'&s25='+first_delivered_date_to
 +'&s26='+last_delivered_date_from
 +'&s27='+last_delivered_date_to
 +'&s28='+estimated_from
 +'&s29='+estimated_to
 +'&s30='+plan_from
 +'&s31='+plan_to
 +'&s32='+remain_on_hand_from
 +'&s33='+remain_on_hand_to
 +'&s34='+delivered_from
 +'&s35='+delivered_to
 +'&s36='+selling_price_from
 +'&s37='+selling_price_to
 +'&s38='+product_delivered_date_from
 +'&s39='+product_delivered_date_to
 +'&s40='+product_delivered_qty_from
 +'&s41='+product_delivered_qty_to
 +'&s42='+account_id
 +'&s43='+sale_rap_code_related
 +'&s44='+owner_id
 +'&s45='+owner_sales_rap
 +'&s46='+consultant_id
 +'&s47='+consultant_sales_rap
 +'&s48='+architect_id
 +'&s49='+architect_sales_rap
 +'&s50='+construction_id
 +'&s51='+construction_sales_rap
 +'&s52='+interior_id
 +'&s53='+interior_sales_rap
 +'&s54='+maincont_id
 +'&s55='+maincont_sales_rap
 +'&s56='+subcontractor_id
 +'&s57='+subcontractor_sales_rap
 +'&s58='+dealer_id2
 +'&s59='+dealer_sales_rap
 +'&s60='+sup_dealer_id
 +'&s61='+sup_dealer_sales_rap;
    // window.open(url, '_blank');
    LoadURL(url, "birt-result");
    $.messager.progress('close');

}

function rpt_sum_report_of_project_line() {
    var projectorder_no = $("#projectorder_no").val();
    var projectorder_name = $("#projectorder_name").val();
    var project_type =  $("#project_type").combogrid('getValue');
    var project_size =  $("#project_size").combogrid('getValue');
    var project_opportunity =  $("#project_opportunity").combogrid('getValue');
    var sale_rap =  $("#sale_rap").combogrid('getValue');

    var project_open_date_from = $("#project_open_date_from").datebox('getValue');
    if(project_open_date_from != ''){
        var project_open_date_from_split = project_open_date_from.split('/');
        project_open_date_from = project_open_date_from_split[2] + '-' + project_open_date_from_split[1] + '-' + project_open_date_from_split[0];
    }

    var project_open_date_to = $("#project_open_date_to").datebox('getValue');
    if(project_open_date_to != ''){
        var project_open_date_to_split = project_open_date_to.split('/');
        project_open_date_to = project_open_date_to_split[2] + '-' + project_open_date_to_split[1] + '-' + project_open_date_to_split[0];
    }

    var delivery_from_date = $("#delivery_from_date").datebox('getValue');
    if(delivery_from_date != ''){
        var delivery_from_date_split = delivery_from_date.split('/');
        delivery_from_date = delivery_from_date_split[2] + '-' + delivery_from_date_split[1] + '-' + delivery_from_date_split[0];
    }

    var delivery_to_date = $("#delivery_to_date").datebox('getValue');
    if(delivery_to_date != ''){
        var delivery_to_date_split = delivery_to_date.split('/');
        delivery_to_date = delivery_to_date_split[2] + '-' + delivery_to_date_split[1] + '-' + delivery_to_date_split[0];
    }

    var total_est_from = $("#total_est_from").val();
    var total_est_to = $("#total_est_to").val();

    var total_plan_from = $("#total_plan_from").val();
    var total_plan_to = $("#total_plan_to").val();

    var total_on_hand_from = $("#total_on_hand_from").val();
    var total_on_hand_to = $("#total_on_hand_to").val();

    var total_deli_from = $("#total_deli_from").val();
    var total_deli_to = $("#total_deli_to").val();

    var product_code_crm = $("#product_code_crm").val();
    var productname = $("#productname").val();

    var product_brand =  $("#product_brand").combogrid('getValue');
    var dealer_id =  $("#dealer_id").val();

    var product_remark = $("#product_remark").val();
    
    var first_delivered_date_from = $("#first_delivered_date_from").datebox('getValue');
    if(first_delivered_date_from != ''){
    var first_delivered_date_from_split = first_delivered_date_from.split('/');
        first_delivered_date_from = first_delivered_date_from_split[2] + '-' + first_delivered_date_from_split[1] + '-' + first_delivered_date_from_split[0];
    }

    var first_delivered_date_to = $("#first_delivered_date_to").datebox('getValue');
    if(first_delivered_date_to != ''){
        var first_delivered_date_to_split = first_delivered_date_to.split('/');
        first_delivered_date_to = first_delivered_date_to_split[2] + '-' + first_delivered_date_to_split[1] + '-' + first_delivered_date_to_split[0];
    }

    var last_delivered_date_from = $("#last_delivered_date_from").datebox('getValue');
    if(last_delivered_date_from != ''){
        var last_delivered_date_from_split = last_delivered_date_from.split('/');
        last_delivered_date_from = last_delivered_date_from_split[2] + '-' + last_delivered_date_from_split[1] + '-' + last_delivered_date_from_split[0];
    }

    var last_delivered_date_to = $("#last_delivered_date_to").datebox('getValue');
    if(last_delivered_date_to != ''){
        var last_delivered_date_to_split = last_delivered_date_to.split('/');
        last_delivered_date_to = last_delivered_date_to_split[2] + '-' + last_delivered_date_to_split[1] + '-' + last_delivered_date_to_split[0]; 
    }
        
    var estimated_from = $("#estimated_from").val();
    var estimated_to = $("#estimated_to").val(); 

    var plan_from = $("#plan_from").val(); 
    var plan_to = $("#plan_to").val();

    var remain_on_hand_from = $("#remain_on_hand_from").val(); 
    var remain_on_hand_to = $("#remain_on_hand_to").val();

    var delivered_from = $("#delivered_from").val(); 
    var delivered_to = $("#delivered_to").val(); 

    var selling_price_from = $("#selling_price_from").val(); 
    var selling_price_to = $("#selling_price_to").val();
    
    var product_delivered_date_from = $("#product_delivered_date_from").datebox('getValue');
    if(product_delivered_date_from != ''){
        var product_delivered_date_from_split = product_delivered_date_from.split('/');
        product_delivered_date_from = product_delivered_date_from_split[2] + '-' + product_delivered_date_from_split[1] + '-' + product_delivered_date_from_split[0];
    }

    var product_delivered_date_to = $("#product_delivered_date_to").datebox('getValue');
    if(product_delivered_date_to != ''){
        var product_delivered_date_to_split = product_delivered_date_to.split('/');
        product_delivered_date_to = product_delivered_date_to_split[2] + '-' + product_delivered_date_to_split[1] + '-' + product_delivered_date_to_split[0];
    }

    var product_delivered_qty_from = $("#product_delivered_qty_from").val(); 
    var product_delivered_qty_to = $("#product_delivered_qty_to").val();

    var account_id =  $("#account_id").val();
    var sale_rap_code_related =  $("#sale_rap_code_related").combogrid('getValue');
    
    var owner_id = $("#owner_id").val();
    var owner_sales_rap = $("#owner_sales_rap").combogrid('getValue');

    var consultant_id =  $("#consultant_id").val();
    var consultant_sales_rap = $("#consultant_sales_rap").combogrid('getValue');

    var architect_id = $("#architect_id").val();
    var architect_sales_rap = $("#architect_sales_rap").combogrid('getValue');

    var construction_id = $("#construction_id").val();
    var construction_sales_rap = $("#construction_sales_rap").combogrid('getValue');

    var interior_id =  $("#interior_id").val();
    var interior_sales_rap = $("#interior_sales_rap").combogrid('getValue');

    var maincont_id = $("#maincont_id").val();
    var maincont_sales_rap = $("#maincont_sales_rap").combogrid('getValue');

    var subcontractor_id = $("#subcontractor_id").val();
    var subcontractor_sales_rap = $("#subcontractor_sales_rap").combogrid('getValue');

    var dealer_id2 = $("#dealer_id2").val();
    var dealer_sales_rap = $("#dealer_sales_rap").combogrid('getValue');

    var sup_dealer_id = $("#sup_dealer_id").val();
    var sup_dealer_sales_rap = $("#sup_dealer_sales_rap").combogrid('getValue');

    var form_data = $('#dataForm').serialize();
    var returnvalid = checkvalid();
    if (returnvalid === false) {
        return false;
    }
    $.messager.progress();
    Filter_report();
    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_sum_report_of_project_line_excel.rptdesign&__showtitle=false'
    +'&s1='+projectorder_no
 +'&s2='+projectorder_name
 +'&s3='+project_type
 +'&s4='+project_size
 +'&s5='+project_opportunity
 +'&s6='+sale_rap
 +'&s7='+project_open_date_from
 +'&s8='+project_open_date_to
 +'&s9='+delivery_from_date
 +'&s10='+delivery_to_date
 +'&s11='+total_est_from
 +'&s12='+total_est_to
 +'&s13='+total_plan_from
 +'&s14='+total_plan_to
 +'&s15='+total_on_hand_from
 +'&s16='+total_on_hand_to
 +'&s17='+total_deli_from
 +'&s18='+total_deli_to
 +'&s19='+product_code_crm
 +'&s20='+productname
 +'&s21='+product_brand
 +'&s22='+dealer_id
 +'&s23='+product_remark
 +'&s24='+first_delivered_date_from
 +'&s25='+first_delivered_date_to
 +'&s26='+last_delivered_date_from
 +'&s27='+last_delivered_date_to
 +'&s28='+estimated_from
 +'&s29='+estimated_to
 +'&s30='+plan_from
 +'&s31='+plan_to
 +'&s32='+remain_on_hand_from
 +'&s33='+remain_on_hand_to
 +'&s34='+delivered_from
 +'&s35='+delivered_to
 +'&s36='+selling_price_from
 +'&s37='+selling_price_to
 +'&s38='+product_delivered_date_from
 +'&s39='+product_delivered_date_to
 +'&s40='+product_delivered_qty_from
 +'&s41='+product_delivered_qty_to
 +'&s42='+account_id
 +'&s43='+sale_rap_code_related
 +'&s44='+owner_id
 +'&s45='+owner_sales_rap
 +'&s46='+consultant_id
 +'&s47='+consultant_sales_rap
 +'&s48='+architect_id
 +'&s49='+architect_sales_rap
 +'&s50='+construction_id
 +'&s51='+construction_sales_rap
 +'&s52='+interior_id
 +'&s53='+interior_sales_rap
 +'&s54='+maincont_id
 +'&s55='+maincont_sales_rap
 +'&s56='+subcontractor_id
 +'&s57='+subcontractor_sales_rap
 +'&s58='+dealer_id2
 +'&s59='+dealer_sales_rap
 +'&s60='+sup_dealer_id
 +'&s61='+sup_dealer_sales_rap
 +'&__format=xls';
    // LoadURL(url, "birt-result");
    window.open(url, '_blank');
    $.messager.progress('close');

}

function Export_report_pdf() {
    var projectorder_no = $("#projectorder_no").val();
    var projectorder_name = $("#projectorder_name").val();
    var project_type =  $("#project_type").combogrid('getValue');
    var project_size =  $("#project_size").combogrid('getValue');
    var project_opportunity =  $("#project_opportunity").combogrid('getValue');
    var sale_rap =  $("#sale_rap").combogrid('getValue');

    
    var project_open_date_from = $("#project_open_date_from").datebox('getValue');
    if(project_open_date_from != ''){
        var project_open_date_from_split = project_open_date_from.split('/');
        project_open_date_from = project_open_date_from_split[2] + '-' + project_open_date_from_split[1] + '-' + project_open_date_from_split[0];
    }

    var project_open_date_to = $("#project_open_date_to").datebox('getValue');
    if(project_open_date_to != ''){
        var project_open_date_to_split = project_open_date_to.split('/');
        project_open_date_to = project_open_date_to_split[2] + '-' + project_open_date_to_split[1] + '-' + project_open_date_to_split[0];
    }

    var delivery_from_date = $("#delivery_from_date").datebox('getValue');
    if(delivery_from_date != ''){
        var delivery_from_date_split = delivery_from_date.split('/');
        delivery_from_date = delivery_from_date_split[2] + '-' + delivery_from_date_split[1] + '-' + delivery_from_date_split[0];
    }

    var delivery_to_date = $("#delivery_to_date").datebox('getValue');
    if(delivery_to_date != ''){
        var delivery_to_date_split = delivery_to_date.split('/');
        delivery_to_date = delivery_to_date_split[2] + '-' + delivery_to_date_split[1] + '-' + delivery_to_date_split[0];
    }

    var total_est_from = $("#total_est_from").val();
    var total_est_to = $("#total_est_to").val();

    var total_plan_from = $("#total_plan_from").val();
    var total_plan_to = $("#total_plan_to").val();

    var total_on_hand_from = $("#total_on_hand_from").val();
    var total_on_hand_to = $("#total_on_hand_to").val();

    var total_deli_from = $("#total_deli_from").val();
    var total_deli_to = $("#total_deli_to").val();

    var product_code_crm = $("#product_code_crm").val();
    var productname = $("#productname").val();

    var product_brand =  $("#product_brand").combogrid('getValue');
    var dealer_id =  $("#dealer_id").val();

    var product_remark = $("#product_remark").val();
    
    var first_delivered_date_from = $("#first_delivered_date_from").datebox('getValue');
    if(first_delivered_date_from != ''){
    var first_delivered_date_from_split = first_delivered_date_from.split('/');
        first_delivered_date_from = first_delivered_date_from_split[2] + '-' + first_delivered_date_from_split[1] + '-' + first_delivered_date_from_split[0];
    }

    var first_delivered_date_to = $("#first_delivered_date_to").datebox('getValue');
    if(first_delivered_date_to != ''){
        var first_delivered_date_to_split = first_delivered_date_to.split('/');
        first_delivered_date_to = first_delivered_date_to_split[2] + '-' + first_delivered_date_to_split[1] + '-' + first_delivered_date_to_split[0];
    }

    var last_delivered_date_from = $("#last_delivered_date_from").datebox('getValue');
    if(last_delivered_date_from != ''){
        var last_delivered_date_from_split = last_delivered_date_from.split('/');
        last_delivered_date_from = last_delivered_date_from_split[2] + '-' + last_delivered_date_from_split[1] + '-' + last_delivered_date_from_split[0];
    }

    var last_delivered_date_to = $("#last_delivered_date_to").datebox('getValue');
    if(last_delivered_date_to != ''){
        var last_delivered_date_to_split = last_delivered_date_to.split('/');
        last_delivered_date_to = last_delivered_date_to_split[2] + '-' + last_delivered_date_to_split[1] + '-' + last_delivered_date_to_split[0]; 
    }
        
    var estimated_from = $("#estimated_from").val();
    var estimated_to = $("#estimated_to").val(); 

    var plan_from = $("#plan_from").val(); 
    var plan_to = $("#plan_to").val();

    var remain_on_hand_from = $("#remain_on_hand_from").val(); 
    var remain_on_hand_to = $("#remain_on_hand_to").val();

    var delivered_from = $("#delivered_from").val(); 
    var delivered_to = $("#delivered_to").val(); 

    var selling_price_from = $("#selling_price_from").val(); 
    var selling_price_to = $("#selling_price_to").val();
    
    var product_delivered_date_from = $("#product_delivered_date_from").datebox('getValue');
    if(product_delivered_date_from != ''){
        var product_delivered_date_from_split = product_delivered_date_from.split('/');
        product_delivered_date_from = product_delivered_date_from_split[2] + '-' + product_delivered_date_from_split[1] + '-' + product_delivered_date_from_split[0];
    }

    var product_delivered_date_to = $("#product_delivered_date_to").datebox('getValue');
    if(product_delivered_date_to != ''){
        var product_delivered_date_to_split = product_delivered_date_to.split('/');
        product_delivered_date_to = product_delivered_date_to_split[2] + '-' + product_delivered_date_to_split[1] + '-' + product_delivered_date_to_split[0];
    }

    var product_delivered_qty_from = $("#product_delivered_qty_from").val(); 
    var product_delivered_qty_to = $("#product_delivered_qty_to").val();

    var account_id =  $("#account_id").val();
    var sale_rap_code_related =  $("#sale_rap_code_related").combogrid('getValue');
    
    var owner_id = $("#owner_id").val();
    var owner_sales_rap = $("#owner_sales_rap").combogrid('getValue');

    var consultant_id =  $("#consultant_id").val();
    var consultant_sales_rap = $("#consultant_sales_rap").combogrid('getValue');

    var architect_id = $("#architect_id").val();
    var architect_sales_rap = $("#architect_sales_rap").combogrid('getValue');

    var construction_id = $("#construction_id").val();
    var construction_sales_rap = $("#construction_sales_rap").combogrid('getValue');

    var interior_id =  $("#interior_id").val();
    var interior_sales_rap = $("#interior_sales_rap").combogrid('getValue');

    var maincont_id = $("#maincont_id").val();
    var maincont_sales_rap = $("#maincont_sales_rap").combogrid('getValue');

    var subcontractor_id = $("#subcontractor_id").val();
    var subcontractor_sales_rap = $("#subcontractor_sales_rap").combogrid('getValue');

    var dealer_id2 = $("#dealer_id2").val();
    var dealer_sales_rap = $("#dealer_sales_rap").combogrid('getValue');

    var sup_dealer_id = $("#sup_dealer_id").val();
    var sup_dealer_sales_rap = $("#sup_dealer_sales_rap").combogrid('getValue');

    var form_data = $('#dataForm').serialize();
    var returnvalid = checkvalid();
    if (returnvalid === false) {
        return false;
    }
    $.messager.progress();
    Filter_report();
    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_sum_report_of_project_line_pdf.rptdesign&__showtitle=false'
 +'&s1='+projectorder_no
 +'&s2='+projectorder_name
 +'&s3='+project_type
 +'&s4='+project_size
 +'&s5='+project_opportunity
 +'&s6='+sale_rap
 +'&s7='+project_open_date_from
 +'&s8='+project_open_date_to
 +'&s9='+delivery_from_date
 +'&s10='+delivery_to_date
 +'&s11='+total_est_from
 +'&s12='+total_est_to
 +'&s13='+total_plan_from
 +'&s14='+total_plan_to
 +'&s15='+total_on_hand_from
 +'&s16='+total_on_hand_to
 +'&s17='+total_deli_from
 +'&s18='+total_deli_to
 +'&s19='+product_code_crm
 +'&s20='+productname
 +'&s21='+product_brand
 +'&s22='+dealer_id
 +'&s23='+product_remark
 +'&s24='+first_delivered_date_from
 +'&s25='+first_delivered_date_to
 +'&s26='+last_delivered_date_from
 +'&s27='+last_delivered_date_to
 +'&s28='+estimated_from
 +'&s29='+estimated_to
 +'&s30='+plan_from
 +'&s31='+plan_to
 +'&s32='+remain_on_hand_from
 +'&s33='+remain_on_hand_to
 +'&s34='+delivered_from
 +'&s35='+delivered_to
 +'&s36='+selling_price_from
 +'&s37='+selling_price_to
 +'&s38='+product_delivered_date_from
 +'&s39='+product_delivered_date_to
 +'&s40='+product_delivered_qty_from
 +'&s41='+product_delivered_qty_to
 +'&s42='+account_id
 +'&s43='+sale_rap_code_related
 +'&s44='+owner_id
 +'&s45='+owner_sales_rap
 +'&s46='+consultant_id
 +'&s47='+consultant_sales_rap
 +'&s48='+architect_id
 +'&s49='+architect_sales_rap
 +'&s50='+construction_id
 +'&s51='+construction_sales_rap
 +'&s52='+interior_id
 +'&s53='+interior_sales_rap
 +'&s54='+maincont_id
 +'&s55='+maincont_sales_rap
 +'&s56='+subcontractor_id
 +'&s57='+subcontractor_sales_rap
 +'&s58='+dealer_id2
 +'&s59='+dealer_sales_rap
 +'&s60='+sup_dealer_id
 +'&s61='+sup_dealer_sales_rap
 +'&__format=pdf';
    // LoadURL(url, "birt-result");
    window.open(url, '_blank');
    $.messager.progress('close');

}


$('#monthly').combogrid({
    onSelect: function(index, row) {
        var monthly_start_date = row.monthly_start_date;
        var monthly_end_date = row.monthly_end_date;

        var m_start_date = monthly_start_date.split('-');
        var start_date = m_start_date[0] + '-' + m_start_date[1] + '-' + m_start_date[2];

        var m_end_date = monthly_end_date.split('-');
        var end_date = m_end_date[0] + '-' + m_end_date[1] + '-' + m_end_date[2];


        $('#date_from').val(start_date);
        $('#date_to').val(end_date);
    }
});

function Send_report(send) {

    var confirmed = window.confirm("Do you want to send this Report to your Reporter?");
    if (confirmed) {
        Filter_report();

        $(send).prop("onclick", null).off("click");
        $.messager.progress();
        var form_data = $('#dataForm').serialize();

        var returnvalid = checkval_send();

        if (returnvalid === false) {
            $.messager.progress('close');
            $(send).click(function() {
                Send_report(send);
            });
            return false;
        }


        var url = '<?php echo site_url("customizereport/sendmail_rpt_report")?>';

        var monthly = $("#monthly").combobox('getText');
        var sendDateStart = $("#date_from").val();
        var sendDateStop = $("#date_to").val();
        var form_data = {
            date_start: sendDateStart,
            date_end: sendDateStop,
            submodule: 'rpt_project_on_hand',
            send_to: $("#send_to").combogrid('getValues'),
            send_cc: $("#send_cc").combogrid('getValues'),
            monthly: monthly
        };

        $.ajax(url, {
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success: function(data) {
                console.log(data);

                jQuery.post(data.url, data.param, function(res) {
                    console.log(res)

                    var obj = res;
                    $.messager.progress('close');
                    $('#dialog').dialog('close')
                    var errMsg = obj.Message;

                    $.messager.alert('Info', errMsg, 'info', function() {
                        if (odj.Type == "S") {
                            window.close();

                        } else {
                            console.log(obj);
                        }
                    });
                    //   $(send).click(function(){
                    //       Send(send);
                    //   });
                }, 'json');

            },
            error: function(data) {
                console.log(data);
                $.messager.progress('close');
                $.messager.alert('Retrieve data', data, 'error');
            }
        });
    }
}
</script>