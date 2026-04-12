<?php global $site_URL;?>
<div style="height: 780px;">
    <form id="dataForm" class="easyui-form" method="POST" name="dataForm" style="height: 100%">
        <div class="row">
            <div class="col-lg-12">
                <!-- <div id="layout" class="easyui-layout" style="width:100%;height:680px"> -->
                <div id="layout" class="easyui-layout" style="width:100%;height:780px">

                    <div class="row">
                        <div class="col-lg-8">
                            <!-- <div data-options="region:'north',split:true" title="Filter"
                                style="width:100%; height:340px;" />
                            <div style="clear:both; height:8px; "></div> -->
                            <div class="row panel-body layout-body" style="height: 120px; padding-top:15px;">
                                 <!-- // -->
                                 <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Opportunity</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox project_opportunity" data-options="required:false" id="project_opportunity"
                                        name="project_opportunity" style="width:100%; height:25px;" />
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Status</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox projectorder_status" data-options="required:false" id="projectorder_status"
                                        name="projectorder_status" style="width:100%; height:25px;" />
                                </div>
                                <!-- //box1 -->

                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="row panel-body layout-body" style="height: 120px; padding-top:15px;">
                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Filter_report()"
                                        style="width:120px;"><i class="fa fa-search"></i>
                                        Search</a>
                                </div>
                                <div style="clear:both; height:8px; "></div>

                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Export_report()"
                                        style="width:120px;"><img src="../../../themes/softed/images/pdf.png" hspace="5"
                                            align="absmiddle" border="0" style="width: 18px;">
                                        Export PDF</a>
                                </div>
                                <!-- <div style="clear:both; height:8px; "></div>

                                <div class="col-xs-12" style="text-align:center">
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

        <input type="hidden" name="sale_name" id="sale_name" value="<?php echo $sale_name;?>">
        <input type="hidden" name="sale_team" id="sale_team" value="<?php echo $sale_team;?>">
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
    GetProjectOpportunity();
    GetProjectOrderStatus();

    /* on load ref file assets/js/utilities.js */
    $("#dialog").css("display", "none");

    $('.row').resize(function() {
        $('#dg').datagrid('resize');
        $('#layout').layout('resize');
    });
});

function checkvalid() {
    if ($("#project_opportunity").combogrid('getValue') == '') {
        $('#project_opportunity').next().find('input').focus();
        return false;
    }else if ($("#date_to").combogrid('getValue') == '') {
        $('#date_to').next().find('input').focus();
        return false;
    } else {
        return true;
    }
}

function checkval_send() {
    if($("#project_opportunity").combogrid('getValues') == ''){
        $('#project_opportunity').next().find('input').focus();
        return false;
    }else if ($("#date_from").combogrid('getValue') == '') {
        $('#date_from').next().find('input').focus();
        return false;
    } else {
        return true;
    }
}

function Filter_report() {
    var project_opportunity =  $("#project_opportunity").combogrid('getValue');
    var projectorder_status =  $("#projectorder_status").combogrid('getValue');
    var sale_name =  $("#sale_name").val();
    var sale_team =  $("#sale_team").val();


    var form_data = $('#dataForm').serialize();
    // var returnvalid = checkvalid();
    // if (returnvalid === false) {
    //     return false;
    // }
    $.messager.progress();

    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_sales_team_forecast_report_by_period.rptdesign&__showtitle=false&project_opportunity=' +
    encodeURIComponent(project_opportunity)+'&projectorder_status='+encodeURIComponent(projectorder_status)+'&sale_name='+sale_name+'&sale_team='+sale_team;
    // alert(url);
    LoadURL(url, "birt-result");
    $.messager.progress('close');
    
}

function Export_report() {
   var project_opportunity =  $("#project_opportunity").combogrid('getValue');
   var projectorder_status =  $("#projectorder_status").combogrid('getValue');
   var sale_name =  $("#sale_name").val();
    var sale_team =  $("#sale_team").val()

    var form_data = $('#dataForm').serialize();
    // var returnvalid = checkvalid();
    // if (returnvalid === false) {
    //     return false;
    // }
    $.messager.progress();
    Filter_report();
    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_sales_team_forecast_report_by_period.rptdesign&__showtitle=false&project_opportunity=' +
    encodeURIComponent(project_opportunity)+'&projectorder_status='+encodeURIComponent(projectorder_status)+'&sale_name='+sale_name+'&sale_team='+sale_team+'&__format=pdf';
    // LoadURL(url, "birt-result");
    window.open(url, '_blank');
    $.messager.progress('close');
    
}




</script>