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
                            <div class="row panel-body layout-body" style="height: 120px; padding-top:15px;">
                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">Send To</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox send_to" id="send_to" name="send_to"
                                        style="width:100%;height:25px;" />
                                </div>

                                <div class="col-xs-2" style="text-align:right;">
                                    <label for="menu">CC</label>
                                </div>
                                <div class="col-xs-4">
                                    <input class="easyui-textbox send_cc " id="send_cc" name="send_cc"
                                        style="width:100%;height:25px;" />
                                </div>

                                <div style="clear:both; height:8px; "></div>
                                    <div class="col-xs-2" style="text-align:right;">
                                        <label for="menu">Date </label>
                                    </div>
                                    <div class="col-xs-4">
                                        <input type="text" class="easyui-datebox" id="date" name="date" value="<?php echo date('Y-m-d');?>"  style="height:25pt;" />
                                    </div>
                                </div>

                            <input type="hidden" name="date_from" id="date_from" value="">
                            <input type="hidden" name="date_to" id="date_to" value="">
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
                                <div style="clear:both; height:8px; "></div>

                                <div class="col-xs-12" style="text-align:center">
                                    <a href="#" class="easyui-linkbutton" onclick="Send_report()"
                                        style="width:120px;"><i class="fa fa-envelope"></i>
                                        Send Report</a>
                                </div>
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

    Getsendtofromuser_sendto('<?php echo USERID ?>');
    Getmonthly_Report();
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
    var date = $("#date").datebox('getValue');
    if(date != ''){
        var date_split = date.split('/');
        date = date_split[2] + '-' + date_split[1] + '-' + date_split[0];
    }

    var form_data = $('#dataForm').serialize();
    var returnvalid = checkvalid();
    if (returnvalid === false) {
        return false;
    }
    $.messager.progress();

    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_daily_new_project.rptdesign&__showtitle=false&date=' +
    date;
    LoadURL(url, "birt-result");
    $.messager.progress('close');
    
}

function Export_report() {
    var date = $("#date").datebox('getValue');
    if(date != ''){
        var date_split = date.split('/');
        date = date_split[2] + '-' + date_split[1] + '-' + date_split[0];
    }

    var form_data = $('#dataForm').serialize();
    var returnvalid = checkvalid();
    if (returnvalid === false) {
        return false;
    }
    $.messager.progress();
    Filter_report();
    var url = '<?php echo REPORT_VIEWER_URL;?>rpt_daily_new_project.rptdesign&__showtitle=false&date=' +
    date+ '&__format=pdf';
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
            submodule : 'rpt_daily_new_project',
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