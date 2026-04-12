<?php global $site_URL; ?>
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
                            <div class="row panel-body layout-body" style="height: 240px; padding-top:15px;">

                            <!-- // -->
                                <div style="clear:both; height:20px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Owner</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="owner_id" id="owner_id" type="hidden">  
                                    <input id="owner_name" class="easyui-textbox owner_name" style="width:100%;" data-options="multiple:true">
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Designer</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="designer_id" id="designer_id" type="hidden">  
                                    <input id="designer_name" class="easyui-textbox designer_name" style="width:100%;" data-options="multiple:true">
                                </div>
                            <!-- // -->

                            <!-- // -->
                            <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Consultant</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="consultant_id" id="consultant_id" type="hidden">  
                                    <input id="consultant_name" class="easyui-textbox consultant_name" style="width:100%;" data-options="multiple:true">
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Number</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="projects_no_id" id="projects_no_id" type="hidden">  
                                    <input id="projects_no" class="easyui-textbox projects_no" style="width:100%;" data-options="multiple:true">
                                </div>
                            <!-- // -->

                             <!-- // -->
                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Contractor</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="contractor_id" id="contractor_id" type="hidden">  
                                    <input id="contractor_name" class="easyui-textbox contractor_name" style="width:100%;" data-options="multiple:true">
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="projectsid" id="projectsid" type="hidden">  
                                    <input id="projects_name" class="easyui-textbox projects_name" style="width:100%;" data-options="multiple:true">
                                </div>
                            <!-- // -->

                             <!-- // -->
                             <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Architect Name</label>
                                </div>
                                <div class="col-xs-4">
                                    <input name="architect_id" id="architect_id" type="hidden">  
                                    <input id="architect_name" class="easyui-textbox architect_name" style="width:100%;" data-options="multiple:true">
                                </div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Project Status</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox projectorder_status" data-options="required:false" id="projectorder_status"
                                        name="projectorder_status" style="width:100%; height:25px;" />
                                </div>
                            <!-- // -->


                            <!-- //box2 -->
                               <div style="clear:both; height:20px; "></div>
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">From</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="date_from" name="date_from" value=""  style="height:25pt;" />
                                </div>
                                <div class="col-xs-1" style="text-align:center;">
                                    <label for="menu">To</label>
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" class="easyui-datebox" id="date_to" name="date_to" value=""  style="height:25pt;" />
                                </div>
                                <!-- // -->

                            </div>

                            <!-- <input type="hidden" name="date_from" id="date_from" value="">
                            <input type="hidden" name="date_to" id="date_to" value=""> -->
                        </div>
                        <div class="col-lg-4">
                            <div class="row panel-body layout-body" style="height: 240px; padding-top:15px;">
                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Filter_report()"
                                        style="width:120px;"><i class="fa fa-search"></i>
                                        Search</a>
                                </div>
                                <div style="clear:both; height:8px; "></div>

                                <div style="clear:both; height:8px; "></div>
                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Export_report_excel()"
                                        style="width:120px;"><img src="../../../themes/softed/images/actionGenerateExcel.png" hspace="5"
                                            align="absmiddle" border="0" style="width: 18px;">
                                        Export Excel</a>
                                </div>

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

    // Getsendtofromuser_sendto('<?php echo USERID ?>');
    // Getmonthly_Report();
    PopupdesignerName();
    PopupProjectsNo();
    PopupProjectsName();
    PopupcontractorName();
    GetProjectOrderStatus();

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
        return true;
    // }
}

function checkval_send() {
    if($("#send_to").combogrid('getValues') == ''){
        $('#send_to').next().find('input').focus();
        return false;
    // }else if ($("#send_cc").combogrid('getValues') == '') {
    //     $('#send_cc').next().find('input').focus();
    //     return false;
    }else if ($("#monthly").combogrid('getValue') == '') {
        $('#monthly').next().find('input').focus();
        return false;
    } else {
        return true;
    }
}

function Filter_report() {
    var owner_id = $("#owner_id").val();
    var designer_id = $("#designer_id").val();
    var consultant_id = $("#consultant_id").val();
    var projects_no = $("#projects_no_id").val();
    var contractor_id = $("#contractor_id").val();
    var projectsid = $("#projectsid").val();
    var architect_id = $("#architect_id").val();
    var projectorder_status =  $("#projectorder_status").combogrid('getValues');
        projectorder_status.join(',');

    var date_from = $("#date_from").datebox('getValue');
    if(date_from != ''){
        var date_split = date_from.split('/');
        date_from = date_split[2] + '-' + date_split[1] + '-' + date_split[0];
    }

    var date_to = $("#date_to").datebox('getValue');
    if(date_to != ''){
        var date_split = date_to.split('/');
        date_to = date_split[2] + '-' + date_split[1] + '-' + date_split[0];
    }

    var form_data = $('#dataForm').serialize();
    var returnvalid = checkvalid();
    if (returnvalid === false) {
        return false;
    }
    $.messager.progress();

    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_project_listing.rptdesign&__showtitle=false'
    +'&ownerid='+owner_id
    +'&designerid='+designer_id
    +'&consultantid='+consultant_id
    +'&projects_no='+projects_no
    +'&contractorid='+contractor_id
    +'&projectsid='+projectsid
    +'&architectid='+architect_id
    +'&projectorder_status='+projectorder_status
    +'&date_from=' +date_from
    +'&date_to='+date_to;
    LoadURL(url, "birt-result");
    // window.open(url, '_blank');
    $.messager.progress('close');

    var store = "call rpt_project_listing('"+owner_id+"','"+designer_id+"','"+consultant_id+"','"+projects_no+"','"+contractor_id+"','"+projectsid+"','"+architect_id+"','"+projectorder_status+"','"+date_from+"','"+date_to+"');";
    Logs("Project listing","rpt_project_listing",url,store);
    
}

function Export_report_excel() {
    var owner_id = $("#owner_id").val();
    var designer_id = $("#designer_id").val();
    var consultant_id = $("#consultant_id").val();
    var projects_no = $("#projects_no_id").val();
    var contractor_id = $("#contractor_id").val();
    var projectsid = $("#projectsid").val();
    var architect_id = $("#architect_id").val();
    var projectorder_status =  $("#projectorder_status").combogrid('getValues');
        projectorder_status.join(',');

    var date_from = $("#date_from").datebox('getValue');
    if(date_from != ''){
        var date_split = date_from.split('/');
        date_from = date_split[2] + '-' + date_split[1] + '-' + date_split[0];
    }

    var date_to = $("#date_to").datebox('getValue');
    if(date_to != ''){
        var date_split = date_to.split('/');
        date_to = date_split[2] + '-' + date_split[1] + '-' + date_split[0];
    }

    var form_data = $('#dataForm').serialize();
    var returnvalid = checkvalid();
    if (returnvalid === false) {
        return false;
    }
    $.messager.progress();

    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_project_listing.rptdesign&__showtitle=false'
    +'&ownerid='+owner_id
    +'&designerid='+designer_id
    +'&consultantid='+consultant_id
    +'&projects_no='+projects_no
    +'&contractorid='+contractor_id
    +'&projectsid='+projectsid
    +'&architectid='+architect_id
    +'&projectorder_status='+projectorder_status
    +'&date_from=' +date_from
    +'&date_to='+date_to
    +'&__format=xls';

    Filter_report();

    // LoadURL(url, "birt-result");
    window.open(url, '_blank');
    $.messager.progress('close');

}

function Logs(report_name,log_name,url,store){
    var form_data = {
        report_name: report_name,
        log_name:log_name,
        url: url,
        store:store,
        user_open_report:"<?php echo USERNAME?>"
    };
    jQuery.ajax('../../../saveLogsReport.php', {
        type: 'POST',
        dataType: 'json',
        data: form_data,
        async: false,
        success: function (data) {
            console.log(data);
        },
        error: function (data) {
            console.log(data);
        }
    });
}

</script>