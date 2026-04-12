<?php global $site_URL; ?>
<div style="height: 780px;">
    <form id="searchForm" class="easyui-form" method="POST" name="searchForm" style="height: 100%">
        <div class="row">
            <div class="col-lg-12">
                <!-- <div id="layout" class="easyui-layout" style="width:100%;height:680px"> -->
                <div id="layout" class="easyui-layout" style="width:100%;height:780px">



               
                    <div data-options="region:'north',split:true" title="Filter" style="width:100%; height:300px;"/>

                    <div class="col-lg-10">
                    <div style="clear:both; height:40px; "></div>

                    <!-- //box1 -->
                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">ช่วงวันที่</label>
                    </div>
                    <div class="col-xs-2" style="text-align:left;">
                        <input type="text" class="easyui-datebox" id="date_from" name="date_from" value=""  style="height:25pt;" />
                    </div>
                    <div class="col-xs-2" style="text-align:center; margin-left:40px;">
                        <label>ถึง</label>
                    </div>
                    <div class="col-xs-2" style="text-align:left;">
                        <input type="text" class="easyui-datebox" id="date_to" name="date_to" value=""  style="height:25pt;" />
                    </div>
                    <!-- //box1 -->

                    <div style="clear:both; height:15px; "></div>
                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">Sales Team <font style="color:red">*</font></label>
                    </div>
                    <div class="col-xs-4">
                        <input class="easyui-textbox sales_team easyui-validatebox" data-options="required:true" id="sales_team" name="sales_team" style="width:100%; height:25px;">
                    </div>
                    <div class="col-xs-1" style="text-align:right;">
                        <label for="menu">Sales <font style="color:red">*</font></label>
                    </div>
                    <div class="col-xs-4">
                        <input class="easyui-textbox sales easyui-validatebox" data-options="required:true" id="sales" name="sales" value="" style="width:100%; height:25px;">
                    </div>

                    <!-- //box2 -->
                    <div style="clear:both; height:15px; "></div>
                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">Stage</label>
                    </div>
                    <div class="col-xs-2">
                        <input class="easyui-textbox stage easyui-validatebox" data-options="required:false" id="stage" name="stage" value="" style="width:100%; height:25px;">
                    </div>

                    <div class="col-xs-1" style="text-align:right; display:none;">
                        <label for="menu">Status</label>
                    </div>
                    <div class="col-xs-2" style="display:none;">
                        <input class="easyui-textbox status easyui-validatebox" data-options="required:true" id="status" name="status" value="" style="width:100%; height:25px;">
                    </div>
                    <!-- //box2 -->

                    
                    <div style="clear:both; height:20px; "></div>
                    </div><!--co-lg-10-->
                    
                    
                    <div class="col-lg-2">
                        <div class="row panel-body layout-body" style="height: 300px; padding-top:15px; border-color: #AED0EA !important;">
                            <div style="clear:both; height:20px; "></div>
                            <div class="col-xs-12" style="text-align:center">
                                <a href="#" class="easyui-linkbutton" onclick="Filter_report()" style="width:120px;"><i class="fa fa-search"></i>Search</a>
                            </div>
                            <div style="clear:both; height:8px; "></div>

                            <div style="clear:both; height:8px; "></div>
                            <div class="col-xs-12" style="text-align:center">
                                <a href="#" class="easyui-linkbutton" onclick="Export_report_excel()" style="width:120px;"><img src="../../../themes/softed/images/actionGenerateExcel.png" hspace="5" align="absmiddle" border="0" style="width: 18px;"> Export Excel</a>
                            </div>         
                        </div>
                    </div><!--co-lg-2-->

                   

                </div><!--region-->

                <div data-options="region:'center',title:'',iconCls:'icon-ok'" id="report-result" style="width:100%; height:540px;">
                    <iframe id="birt-result" style="padding:0 0 0 0; min-height:500px;" frameborder="0" width="100%" height="auto">
                    </iframe>
                </div>

            </div><!--layout-->
        </div> <!--co-lg-12-->

        

    </form>
</div>



<style type="text/css">
.content{
    height: 800px !important;
}

</style>
<script>
    $(document).ready(function () {
        Get_Sales();
        Get_Sales_team();
        Get_Stage();
        Get_Status();

        $('#sales').combogrid({
            'value': '<?php echo USERID?>',
        });

        $('#sales_team').combogrid({
            'value': '<?php echo $role_user;?>',
        });

    });

    function checkvalid() {
        if ($("#sales_team").combogrid('getValues') == '') {
            $('#sales_team').next().find('input').focus();
            return false;
        }else if ($("#sales").combogrid('getValues') == '') {
            $('#sales').next().find('input').focus();
            return false;
        } else {
            return true;
        }
    }

    function Filter_report() {
        var date_from = $("#date_from").datebox('getValue');
        if(date_from != ''){
            var date_from_split = date_from.split('/');
            date_from = date_from_split[2] + '-' + date_from_split[1] + '-' + date_from_split[0];
        }

        var date_to = $("#date_to").datebox('getValue');
        if(date_to != ''){
            var date_to_split = date_to.split('/');
            date_to = date_to_split[2] + '-' + date_to_split[1] + '-' + date_to_split[0];
        }

        var roleid =  $("#sales_team").combogrid('getValues');
            roleid.join(',');

        var userid =  $("#sales").combogrid('getValues');
            userid.join(',');

        var stage =  $("#stage").combogrid('getValues');
            // stage.join(',');

        var status =  $("#status").combogrid('getValues');
            // status.join(',');

        var combinedArray = stage.concat(status);
        var stageid = combinedArray.join(',');

        var returnvalid = checkvalid();
            if (returnvalid === false) {
                return false;
            }


        var url = '<?php echo REPORT_VIEWER_URL;?>rpt_pipeline_detail.rptdesign&__showtitle=false'
        +'&date_from='+date_from
        +'&date_to='+date_to
        +'&roleid='+roleid
        +'&userid='+userid
        +'&stageid='+stageid
        // window.open(url, '_blank');
        LoadURL(url, "birt-result");
        $.messager.progress('close');

        var store = "call rpt_pipeline_detail('"+date_from+"','"+date_to+"','"+roleid+"','"+userid+"','"+stageid+"');";
        Logs("Pipeline Detail","rpt_pipeline_detail",url,store);
    }

    function Export_report_excel() {
        var date_from = $("#date_from").datebox('getValue');
        if(date_from != ''){
            var date_from_split = date_from.split('/');
            date_from = date_from_split[2] + '-' + date_from_split[1] + '-' + date_from_split[0];
        }

        var date_to = $("#date_to").datebox('getValue');
        if(date_to != ''){
            var date_to_split = date_to.split('/');
            date_to = date_to_split[2] + '-' + date_to_split[1] + '-' + date_to_split[0];
        }

        var roleid =  $("#sales_team").combogrid('getValues');
            roleid.join(',');

        var userid =  $("#sales").combogrid('getValues');
            userid.join(',');

        var stage =  $("#stage").combogrid('getValues');
            // stage.join(',');

        var status =  $("#status").combogrid('getValues');
            // status.join(',');

        var combinedArray = stage.concat(status);
        var stageid = combinedArray.join(',');

        var returnvalid = checkvalid();
            if (returnvalid === false) {
                return false;
            }


        var url = '<?php echo REPORT_VIEWER_URL;?>rpt_pipeline_detail.rptdesign&__showtitle=false'
        +'&date_from='+date_from
        +'&date_to='+date_to
        +'&roleid='+roleid
        +'&userid='+userid
        +'&stageid='+stageid
        +'&__format=xlsx';

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