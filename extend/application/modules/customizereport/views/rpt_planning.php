<?php global $site_URL; ?>
<div style="height:900px;">
    <form id="dataForm" class="easyui-form" method="POST" name="dataForm" style="height: 100%">
        <div class="row">
            <div class="col-lg-12">
                <!-- <div id="layout" class="easyui-layout" style="width:100%;height:680px"> -->
                <div id="layout" class="easyui-layout" style="width:100%;height:900px">

                    <div class="row">
                        <div class="col-lg-8">
                            <!-- <div data-options="region:'north',split:true" title="Filter"
                                style="width:100%; height:340px;" />
                            <div style="clear:both; height:8px; "></div> -->
                            <div class="row panel-body layout-body" style="height: 430px; padding-top:15px;">
                                
                                <!-- // -->
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Brand Code</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox product_brand" id="product_brand"
                                        name="product_brand" style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Product Code</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox product_code_crm" id="product_code_crm"
                                        name="product_code_crm" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Grade</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox product_grade" id="product_grade"
                                        name="product_grade" style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Product Status</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox product_status" id="product_status"
                                        name="product_status" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Popular Number</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox popular_number" id="popular_number"
                                        name="popular_number" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:25px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Shipping Method</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox shipping_method" id="shipping_method"
                                        name="shipping_method" style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Item Status</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox item_status" id="item_status"
                                        name="item_status" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:8px;"></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Counting PO</label>
                                </div>
                                <div class="col-xs-2">
                                    <input class="easyui-textbox counting_po_from" id="counting_po_from" name="counting_po_from" style="width:100%;height:25px;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">ถึง</label>
                                </div>
                                <div class="col-xs-2">
                                    <input class="easyui-textbox counting_po_to" id="counting_po_to" name="counting_po_to" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:25px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Safety Stock</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox safety_stock" id="safety_stock"
                                        name="safety_stock" style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Order By Safety</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox order_by_safety" id="order_by_safety"
                                        name="order_by_safety" style="width:100%;height:25px;" />
                                </div>
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Safety Month</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox safety_month" id="safety_month"
                                        name="safety_month" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:25px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Final by Order for 2 mth</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox final_by_order_2_mth" id="final_by_order_2_mth"
                                        name="final_by_order_2_mth" style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Purchasing for 2 mth</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox purchasing_2_mth" id="purchasing_2_mth"
                                        name="purchasing_2_mth" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Final by Order for 3 mth</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox final_by_order_3_mth" id="final_by_order_3_mth"
                                        name="final_by_order_3_mth" style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Purchasing for 3 mth</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox purchasing_3_mth" id="purchasing_3_mth"
                                        name="purchasing_3_mth" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->

                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Final by Order for 4 mth</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox final_by_order_4_mth" id="final_by_order_4_mth"
                                        name="final_by_order_4_mth" style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-3" style="text-align:right;">
                                    <label for="menu">Purchasing for 4 mth</label>
                                </div>
                                <div class="col-xs-3">
                                    <input class="easyui-textbox purchasing_4_mth" id="purchasing_4_mth"
                                        name="purchasing_4_mth" style="width:100%;height:25px;" />
                                </div>
                                <!-- // -->
                                    

                            </div>

                            <input type="hidden" name="date_from" id="date_from" value="">
                            <input type="hidden" name="date_to" id="date_to" value="">
                        </div>
                        <div class="col-lg-4">
                            <div class="row panel-body layout-body" style="height: 430px; padding-top:15px;">
                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Filter_report()"
                                        style="width:120px;"><i class="fa fa-search"></i>
                                        Search</a>
                                </div>
                                <div style="clear:both; height:8px; "></div>

                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Export_report()" style="width:120px;"><img src="../../../themes/softed/images/actionGenerateExcel.png" hspace="5" align="absmiddle" border="0" style="width: 18px;">Export Excel</a>
                                </div>
                                <div style="clear:both; height:8px; "></div>

                                <!-- <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Send_report()"
                                        style="width:120px;"><i class="fa fa-envelope"></i>
                                        Send Report</a>
                                </div> -->
                            </div>

                        </div>
                        <div class="col-lg-12" style="text-align:center">
                            <div data-options="region:'center',title:'',iconCls:'icon-ok'" id="report-result"
                                style="width:100%; height:540px;">
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
<div id="dialog"  title="Send Report"  style="width:850px;height:600px; margin-top:10px;">	

</div>
<!--Close Send Report -->

<style type="text/css">
.content {
    height: 800px !important;
}
</style>
<script>
$(document).ready(function() {
    GetProductBrand();
    GetGrade();
    GetProductStatus();
    GetShippingMethod();
    GetItemStatus();
    GetSafetyStock();
    GetOrderBySafety();
    GetSafetyMonth();
    GetFinalbyOrder2mth();
    GetPurchasingfor2mth();
    GetFinalbyOrder3mth();
    GetPurchasingfor3mth();
    GetFinalbyOrder4mth();
    GetPurchasingfor4mth();
    // Getsendtofromuser_sendto('<?php echo USERID ?>');
    // Getmonthly_Report();
    /* on load ref file assets/js/utilities.js */
    $("#dialog").css("display", "none");

    $('.row').resize(function() {
        $('#dg').datagrid('resize');
        $('#layout').layout('resize');
    });
});

function checkvalid() {
    if ($("#date").combogrid('getValue') == '') {
        $('#date').next().find('input').focus();
        return false;
    } else {
        return true;
    }
}

function checkval_send() {
    if($("#send_to").combogrid('getValues') == ''){
        $('#send_to').next().find('input').focus();
        return false;
    // }else if ($("#send_cc").combogrid('getValues') == '') {
    //     $('#send_cc').next().find('input').focus();
    //     return false;
    }else if ($("#date").combogrid('getValue') == '') {
        $('#date').next().find('input').focus();
        return false;
    } else {
        return true;
    }
}

function Filter_report() {
    var product_brand =  $("#product_brand").combogrid('getValue');
    var product_code_crm = $("#product_code_crm").val();
    var product_grade = $("#product_grade").combogrid('getValue');
    var product_status = $("#product_status").combogrid('getValue');
    var popular_number = $("#popular_number").val();
    var shipping_method = $("#shipping_method").combogrid('getValue');
    var item_status = $("#item_status").combogrid('getValue');
    var counting_po_from = $("#counting_po_from").val();
    var counting_po_to = $("#counting_po_to").val();
    var safety_stock = $("#safety_stock").combogrid('getValue');
    var order_by_safety = $("#order_by_safety").combogrid('getValue');
    var safety_month = $("#safety_month").combogrid('getValue');

    var final_by_order_2_mth = $("#final_by_order_2_mth").combogrid('getValue');
    var purchasing_2_mth = $("#purchasing_2_mth").combogrid('getValue');
    var final_by_order_3_mth = $("#final_by_order_3_mth").combogrid('getValue');
    var purchasing_3_mth = $("#purchasing_3_mth").combogrid('getValue');
    var final_by_order_4_mth = $("#final_by_order_4_mth").combogrid('getValue');
    var purchasing_4_mth = $("#purchasing_4_mth").combogrid('getValue');
    // console.log(purchasing_4_mth);

    // var form_data = $('#dataForm').serialize();
    // var returnvalid = checkvalid();
    // if (returnvalid === false) {
    //     return false;
    // }
    $.messager.progress();

    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_planning.rptdesign&__showtitle=false&product_brand='+
    encodeURIComponent(product_brand)+'&product_code_crm='+encodeURIComponent(product_code_crm)+'&product_grade='+encodeURIComponent(product_grade)+'&product_status='+encodeURIComponent(product_status)+'&popular_number='+encodeURIComponent(popular_number)+'&shipping_method='+encodeURIComponent(shipping_method)+'&item_status='+encodeURIComponent(item_status)+'&counting_po_from='+encodeURIComponent(counting_po_from)+'&counting_po_to='+encodeURIComponent(counting_po_to)+'&safety_stock='+encodeURIComponent(safety_stock)+'&order_by_safety='+encodeURIComponent(order_by_safety)+'&safety_month='+encodeURIComponent(safety_month)+'&final_by_order_2_mth='+encodeURIComponent(final_by_order_2_mth)+'&final_by_order_3_mth='+encodeURIComponent(final_by_order_3_mth)+'&final_by_order_4_mth='+encodeURIComponent(final_by_order_4_mth);
    LoadURL(url, "birt-result");
    $.messager.progress('close');
    
}

function Export_report() {
    var product_brand =  $("#product_brand").combogrid('getValue');
    var product_code_crm = $("#product_code_crm").val();
    var product_grade = $("#product_grade").combogrid('getValue');
    var product_status = $("#product_status").combogrid('getValue');
    var popular_number = $("#popular_number").val();
    var shipping_method = $("#shipping_method").combogrid('getValue');
    var item_status = $("#item_status").combogrid('getValue');
    var counting_po_from = $("#counting_po_from").val();
    var counting_po_to = $("#counting_po_to").val();
    var safety_stock = $("#safety_stock").combogrid('getValue');
    var order_by_safety = $("#order_by_safety").combogrid('getValue');
    var safety_month = $("#safety_month").combogrid('getValue');

    var final_by_order_2_mth = $("#final_by_order_2_mth").combogrid('getValue');
    var purchasing_2_mth = $("#purchasing_2_mth").combogrid('getValue');
    var final_by_order_3_mth = $("#final_by_order_3_mth").combogrid('getValue');
    var purchasing_3_mth = $("#purchasing_3_mth").combogrid('getValue');
    var final_by_order_4_mth = $("#final_by_order_4_mth").combogrid('getValue');
    var purchasing_4_mth = $("#purchasing_4_mth").combogrid('getValue');
    // console.log(purchasing_4_mth);

    // var form_data = $('#dataForm').serialize();
    // var returnvalid = checkvalid();
    // if (returnvalid === false) {
    //     return false;
    // }
    $.messager.progress();

    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_planning.rptdesign&__showtitle=false&product_brand='+
    encodeURIComponent(product_brand)+'&product_code_crm='+encodeURIComponent(product_code_crm)+'&product_grade='+encodeURIComponent(product_grade)+'&product_status='+encodeURIComponent(product_status)+'&popular_number='+encodeURIComponent(popular_number)+'&shipping_method='+encodeURIComponent(shipping_method)+'&item_status='+encodeURIComponent(item_status)+'&counting_po_from='+encodeURIComponent(counting_po_from)+'&counting_po_to='+encodeURIComponent(counting_po_to)+'&safety_stock='+encodeURIComponent(safety_stock)+'&order_by_safety='+encodeURIComponent(order_by_safety)+'&safety_month='+encodeURIComponent(safety_month)+'&final_by_order_2_mth='+encodeURIComponent(final_by_order_2_mth)+'&final_by_order_3_mth='+encodeURIComponent(final_by_order_3_mth)+'&final_by_order_4_mth='+encodeURIComponent(final_by_order_4_mth)+'&__format=xls';

    Filter_report();
    // LoadURL(url, "birt-result");
    window.open(url, '_blank');
    $.messager.progress('close');
    
}


function Send_report(send){

    var confirmed = window.confirm("Do you want to send this Report to your Reporter?");
    if (confirmed) {
        Filter_report();
        
        $(send).prop("onclick", null).off("click");
        $.messager.progress();
        var form_data = $('#dataForm').serialize();
        
        var returnvalid = checkval_send();

        if(returnvalid===false){
            $.messager.progress('close');
            $(send).click(function(){
                Send_report(send);
            });
            return false;
        }


        var url = '<?php echo site_url("index.php/customizereport/sendmail_rpt_report")?>';

        var date = $("#date").datebox('getValue');
        if(date != ''){
            var date_split = date.split('/');
            date = date_split[2] + '-' + date_split[1] + '-' + date_split[0];
        }

        var form_data = {
            date : date,
            submodule : 'rpt_planning',
            send_to: $("#send_to").combogrid('getValues'),
            send_cc: $("#send_cc").combogrid('getValues')
        };

        $.ajax(url, {
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {
            console.log(data);

            jQuery.post(data.url, data.param, function(res){
                console.log(res)

            var obj = res;
            $.messager.progress('close');
            $('#dialog').dialog('close')
            var errMsg =  obj.Message;
            
            $.messager.alert('Info', errMsg, 'info', function(){
                if(odj.Type == "S"){
                    window.close();
                    
                }else{
                    console.log(obj);
                }
            });
            //   $(send).click(function(){
            //       Send(send);
            //   });
            },'json');
            
        },
        error: function (data) {
            console.log(data);
        $.messager.progress('close');
        $.messager.alert('Retrieve data', data, 'error');
        }
        });
    }
}

</script>