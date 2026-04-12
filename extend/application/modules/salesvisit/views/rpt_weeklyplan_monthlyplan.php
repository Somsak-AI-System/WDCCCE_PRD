<?php global $site_URL; ?>
<div style="height: 780px;">
    <form id="searchForm" class="easyui-form" method="POST" name="searchForm" style="height: 100%">
        <div class="row">
            <div class="col-lg-12">
                <!-- <div id="layout" class="easyui-layout" style="width:100%;height:680px"> -->
                <div id="layout" class="easyui-layout" style="width:100%;height:780px">

                    <div data-options="region:'north',split:true" title="Filter" style="width:100%; height:340px;"/>
                    <div style="clear:both; height:8px; "></div>

                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">Sales</label>
                    </div>
                    <div class="col-xs-4">
                        <input class="easyui-textbox sales easyui-validatebox" data-options="required:true" id="sales"
                               name="sales" style="width:100%; height:25px;">
                    </div>

                    <input type="hidden" id="area" name="area" style="width:100%; height:25px;">
                    <input type="hidden" id="department" name="department" style="width:100%; height:25px;">

                    <div class="col-xs-2" style="text-align:right">
                        <label for="menu">Account Name</label>
                    </div>
                    <div class="col-xs-4">
                        <input name="account_id" id="account_id" type="hidden">
                        <input id="account_name" class="easyui-textbox account_name" style="width:'100%';height:80px">
                    </div>

                    <div style="clear:both; height:8px; "></div>

                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">Weekly Plan</label>
                    </div>
                    <div class="col-xs-4">
                        <input class="easyui-textbox weekly" id="weekly" name="weekly"
                               style="width:100%; height:25px;"/>
                    </div>

                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">Monthly Plan</label>
                    </div>

                    <div class="col-xs-4">
                        <input class="easyui-textbox monthly" id="monthly" name="monthly"
                               style="width:100%; height:25px;"/>
                    </div>


                    <div style="clear:both; height:8px; "></div>
                    <div class="col-xs-2" style="text-align:right">
                        <label for="menu">Objective</label>
                    </div>
                    <div class="col-xs-4">
                        <input class="easyui-textbox objective easyui-validatebox" data-options="prompt:'ALL Objective'"
                               id="objective" name="objective" style="width:100%; height:25px;"/>
                    </div>

                    <div class="col-xs-2" style="text-align:right">
                        <label for="menu">Report Type</label>
                    </div>
                    <div class="col-xs-4">
                        <input class="easyui-textbox report_type easyui-validatebox" data-options="required:true"
                               id="report_type" name="report_type" style="width:100%; height:25px;"/>
                    </div>

                    <div style="clear:both; height:8px; "></div>
                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">Sales Team</label>
                    </div>
                    <div class="col-xs-4">
                        <input class="easyui-textbox sales_team easyui-validatebox" data-options="required:true" id="sales_team" name="sales_team" style="width:100%; height:25px;">
                    </div>

                    <div style="clear:both; height:8px; "></div>

                    <div class="col-xs-2" style="text-align:right;">
                        <label for="menu">From</label>
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="easyui-datebox" id="date_from" name="date_from"
                               value="<? echo $date_from ?>" style="height:25pt; width:60%;"/>
                    </div>

                    <div class="col-xs-2" style="text-align:right">
                        <label for="menu">To</label>
                    </div>
                    <div class="col-xs-4">
                        <input name="member_id" id="member_id" type="hidden">
                        <input type="text" class="easyui-datebox" id="date_to" name="date_to"
                               value="<? echo $date_to ?>" style="height:25pt; width:60%;"/>
                    </div>

                    <div style="clear:both; height:8px; "></div>

                    <div class="col-xs-6" style="text-align:center">
                        <a href="#" class="easyui-linkbutton" onclick="Filter1()"><i class="fa fa-search"></i> Search</a>
                    </div>

                    <?php /*
                    <div class="col-xs-6" style="text-align:center">
                        <a href="#" class="easyui-linkbutton" onclick="Send_report()"><i class="fa fa-envelope"></i>
                            Send Report</a>
                    </div>

                    <?php if (!empty($role_user)) { ?>
                        <div style="clear:both; height:8px; "></div>

                        <div class="col-xs-6" style="text-align:center"></div>

                        <div class="col-xs-6" style="text-align:center">
                            <a href="#" class="easyui-linkbutton" onclick="Send_weekly_manual()"><i
                                        class="fa fa-envelope"></i>Send Weekly Plan Manual</a>
                        </div>

                        <div style="clear:both; height:8px; "></div>

                        <div class="col-xs-6" style="text-align:center"></div>

                        <div class="col-xs-6" style="text-align:center">
                            <a href="#" class="easyui-linkbutton" onclick="Send_month_manual()"><i
                                        class="fa fa-envelope"></i>Send Monthly Plan Manual</a>
                        </div>
                    <?php } ?>
                    */?>

                </div><!--region-->

                <div data-options="region:'center',title:'',iconCls:'icon-ok'" id="report-result" style="width:100%; height:540px;">
                    <iframe id="birt-result" style="padding:0 0 0 0; min-height:500px;" frameborder="0" width="100%" height="auto">
                    </iframe>
                </div>

            </div><!--layout-->
        </div> <!--co-lg-12-->

    </form>
</div>

<!--Open Send Report -->
<div id="dialog" title="Send Report" style="width:850px;height:600px;">

    <form id="sendForm" class="easyui-form" method="POST" name="sendForm">
        <div style="clear:both; height:8px; "></div>
        <div class="col-xs-2" style="text-align:right">
            <label for="menu">Report Type</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox report_type_model easyui-validatebox" data-options="required:true"
                   id="report_type_model" name="report_type_model" style="width:250px; height:25px;"/>
        </div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">Sales</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox sales_report easyui-validatebox" data-options="required:true" id="sales_report"
                   name="sales_report" style="width:250px; height:25px;">
        </div>

        <div style="clear:both; height:8px; "></div>

        <div id="weekly_grid">
            <div class="col-xs-2" style="text-align:right;">
                <label for="menu">Weekly Plan</label>
            </div>

            <div class="col-xs-4">
                <input class="easyui-textbox weekly_report" id="weekly_report" data-options="required:true"
                       name="weekly_report" style="width:250px; height:25px; display:none">
            </div>

        </div>

        <div id="monthly_grid">
            <div class="col-xs-2" style="text-align:right;">
                <label for="menu">Monthly Plan</label>
            </div>
            <div class="col-xs-4">
                <input class="easyui-textbox monthly_report" id="monthly_report" data-options="required:true"
                       name="monthly_report" style="width:250px; height:25px; display:none">
            </div>
        </div>

        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">From</label>
        </div>
        <div class="col-xs-4">
            <input type="text" class="easyui-datebox" id="date_from_report" name="date_from_report"
                   value="<? echo $date_from ?>" style="height:25pt; width:150px;background-color:#999"
                   readonly="readonly"/>
        </div>

        <div class="col-xs-2" style="text-align:right">
            <label for="menu">To</label>
        </div>
        <div class="col-xs-4">
            <input name="member_id" id="member_id" type="hidden">
            <input type="text" class="easyui-datebox" id="date_to_report" name="date_to_report"
                   value="<? echo $date_to ?>" style="height:25pt; width:150px;background-color:#999" readonly="readonly" />
        </div>

        <div style="clear:both; height:8px; "></div>
        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">Send To</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox send_to easyui-validatebox" data-options="required:true" id="send_to_user"
                   name="send_to_user" style="width:200px; height:25px;"/>
        </div>

        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-12" style="text-align:center">
            <a href="#" class="easyui-linkbutton" onclick="Send(this)" onkeydown="if(event.keyCode==13) return false;"><i class="fa fa-envelope"></i> Send</a>
            <a href="#" class="easyui-linkbutton" onclick="cancel_report()"><i class="fa fa-cancel"></i> Cancel</a>
        </div>
    </form>
</div>
<!--Clase Send Report -->

<!--Open Weekly Plan Manual -->
<div id="dialog_weekly" title="Send Report" style="width:850px; height:100px;">
    <form id="sendForm_weekly" class="easyui-form" method="POST" name="sendForm_weekly">
        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">Weekly Plan</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox weekly_report easyui-validatebox" data-options="required:true" id="weekly_report_admin" name="weekly_report_admin" style="width:250px; height:25px; ">
        </div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">Section</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox section" id="section_weekly" data-options="required:true" name="section_weekly" style="width:250px; height:25px; display:none">
        </div>

        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">From</label>
        </div>
        <div class="col-xs-4">
            <input type="text" class="easyui-datebox" id="date_from_admin_weekly" name="date_from_admin_weekly"
                   value="<? echo $date_from ?>" style="height:25pt; width:150px;background-color:#999"
                   readonly="readonly"/>
        </div>

        <div class="col-xs-2" style="text-align:right">
            <label for="menu">To</label>
        </div>
        <div class="col-xs-4">
            <input name="member_id" id="member_id" type="hidden">
            <input type="text" class="easyui-datebox" id="date_to_admin_weekly" name="date_to_admin_weekly"
                   value="<? echo $date_to ?>" style="height:25pt; width:150px;background-color:#999" readonly="readonly" />
        </div>

        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-12" style="text-align:center">
            <a href="#" class="easyui-linkbutton" onclick="Send_weekly(this)" onkeydown="if(event.keyCode==13) return false;" ><i class="fa fa-envelope"></i>Send</a>
            <a href="#" class="easyui-linkbutton" onclick="cancel_report_weekly()"><i class="fa fa-cancel"></i>Cancel</a>
        </div>
    </form>
</div>
<!--Close Weekly Plan Manual -->

<!--Open Monthy Plan Manual -->
<div id="dialog_month" title="Send Report" style="width:850px; height:100px;">

    <form id="sendForm_month" class="easyui-form" method="POST" name="sendForm_month">
        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">Monthly Plan</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox monthly_report" id="monthly_report_admin" data-options="required:true"
                   name="monthly_report_admin" style="width:250px; height:25px; display:none">
        </div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">Section</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox section" id="section_month" data-options="required:true" name="section_month"
                   style="width:250px; height:25px; display:none">
        </div>

        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">From</label>
        </div>
        <div class="col-xs-4">
            <input type="text" class="easyui-datebox" id="date_from_report_admin" name="date_from_report_admin"
                   value="<? echo $date_from ?>" style="height:25pt; width:150px;background-color:#999"
                   readonly="readonly"/>
        </div>

        <div class="col-xs-2" style="text-align:right">
            <label for="menu">To</label>
        </div>
        <div class="col-xs-4">
            <input name="member_id" id="member_id" type="hidden">
            <input type="text" class="easyui-datebox" id="date_to_report_admin" name="date_to_report_admin"
                   value="<? echo $date_to ?>" style="height:25pt; width:150px;background-color:#999""
            readonly="readonly" />
        </div>

        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-12" style="text-align:center">
            <a href="#" class="easyui-linkbutton" onclick="Send_month(this)" onkeydown="if(event.keyCode==13) return false;"><i class="fa fa-envelope"></i> Send</a>
            <a href="#" class="easyui-linkbutton" onclick="cancel_report_month()"><i class="fa fa-cancel"></i>
                Cancel</a>
        </div>
    </form>
</div>
<!--Close Monthy Plan Manual -->

<style type="text/css">
.content{
    height: 800px !important;
}

</style>
<script>
    $(document).ready(function () {
        /* on load ref file assets/js/utilities.js */
        $("#dialog").css("display", "none");
        $("#dialog_month").css("display", "none");
        $("#dialog_weekly").css("display", "none");

        Getweeklyplan();
        Get_Reporttype();
        Get_Reporttype_send();
        Get_Department();
        Get_Sales();
        Get_Sale_send_new();
        Get_Sale_send();
        Getmonthly_plan();
        Get_Sales_team();

        Get_section('<?php echo $section;?>');

        $('.row').resize(function () {
            $('#dg').datagrid('resize');
            $('#layout').layout('resize');
        });

        $('#sales').combogrid({
            'value': '<?php echo USERID?>',
        });

        $('#sales_report').combogrid({
            'value': '<?php echo USERID?>',
        });

        $('#weekly').combogrid({
            onSelect: function (index, row) {
                var weekly_start_date = row.weekly_start_date;
                var weekly_end_date = row.weekly_end_date;

                var w_start_date = weekly_start_date.split('-');
                var start_date = w_start_date[2] + '/' + w_start_date[1] + '/' + w_start_date[0];

                var w_end_date = weekly_end_date.split('-');
                var end_date = w_end_date[2] + '/' + w_end_date[1] + '/' + w_end_date[0];


                $('#date_from').datebox('setValue', start_date);
                $('#date_to').datebox('setValue', end_date);
            }
        });

        $('#monthly').combogrid({
            onSelect: function (index, row) {
                var monthly_start_date = row.monthly_start_date;
                var monthly_end_date = row.monthly_end_date;

                var m_start_date = monthly_start_date.split('-');
                var start_date = m_start_date[2] + '/' + m_start_date[1] + '/' + m_start_date[0];

                var m_end_date = monthly_end_date.split('-');
                var end_date = m_end_date[2] + '/' + m_end_date[1] + '/' + m_end_date[0];


                $('#date_from').datebox('setValue', start_date);
                $('#date_to').datebox('setValue', end_date);
            }
        });

        $('#weekly_report').combogrid({
            onSelect: function (index, row) {
                var weekly_start_date = row.weekly_start_date;
                var weekly_end_date = row.weekly_end_date;

                var w_start_date = weekly_start_date.split('-');
                var start_date = w_start_date[2] + '/' + w_start_date[1] + '/' + w_start_date[0];

                var w_end_date = weekly_end_date.split('-');
                var end_date = w_end_date[2] + '/' + w_end_date[1] + '/' + w_end_date[0];


                $('#date_from_report').datebox('setValue', start_date);
                $('#date_to_report').datebox('setValue', end_date);
            }
        });

        $('#monthly_report').combogrid({
            onSelect: function (index, row) {
                var monthly_start_date = row.monthly_start_date;
                var monthly_end_date = row.monthly_end_date;

                var m_start_date = monthly_start_date.split('-');
                var start_date = m_start_date[2] + '/' + m_start_date[1] + '/' + m_start_date[0];

                var m_end_date = monthly_end_date.split('-');
                var end_date = m_end_date[2] + '/' + m_end_date[1] + '/' + m_end_date[0];


                $('#date_from_report').datebox('setValue', start_date);
                $('#date_to_report').datebox('setValue', end_date);
            }
        });


        $('#monthly_report_admin').combogrid({
            onSelect: function (index, row) {
                var monthly_start_date = row.monthly_start_date;
                var monthly_end_date = row.monthly_end_date;

                var m_start_date = monthly_start_date.split('-');
                var start_date = m_start_date[2] + '/' + m_start_date[1] + '/' + m_start_date[0];

                var m_end_date = monthly_end_date.split('-');
                var end_date = m_end_date[2] + '/' + m_end_date[1] + '/' + m_end_date[0];


                $('#date_from_report_admin').datebox('setValue', start_date);
                $('#date_to_report_admin').datebox('setValue', end_date);
            }
        });


        $('#weekly_report_admin').combogrid({
            onSelect: function (index, row) {
                var weekly_start_date = row.weekly_start_date;
                var weekly_end_date = row.weekly_end_date;

                var w_start_date = weekly_start_date.split('-');
                var start_date = w_start_date[2] + '/' + w_start_date[1] + '/' + w_start_date[0];

                var w_end_date = weekly_end_date.split('-');
                var end_date = w_end_date[2] + '/' + w_end_date[1] + '/' + w_end_date[0];

                $('#date_from_admin_weekly').datebox('setValue', start_date);
                $('#date_to_admin_weekly').datebox('setValue', end_date);
            }
        });


        $('#report_type_model').combogrid({
            onSelect: function (index, row) {
                var reportcode = row.reportcode;
                if (reportcode == 'Weekly Plan') {
                    $('#monthly_grid').hide();
                    $('#weekly_grid').show();
                } else if (reportcode == 'Monthly Plan') {
                    $('#weekly_grid').hide();
                    $('#monthly_grid').show();
                }

            }
        });


        $('#sales_report').combogrid({
            onSelect: function (index, row) {
                var userid = row.id;
                Getsendtofromuser(userid);
            }
        });


        Getweeklyplan_send();
        Getmonthlyplan_send();
        Getactivitytype_report();
        Getsendtofromuser('<?php echo USERID ?>');

        $('#weekly_grid').hide();
        $('#monthly_grid').hide();


    });

    function checkvalid() {
        if ($("#sales").combogrid('getValues') == '') {
            $('#sales').next().find('input').focus();
            return false;
        } else if ($("#report_type").combogrid('getValue') == '') {
            $('#report_type').next().find('input').focus();
            return false;
        } else {
            return true;
        }
    }

    function Filter1() {

        // var id = $("#sales").combogrid('getValue');
        var id =  $("#sales").combogrid('getValues');
        id.join(',');

        $.post('<?php echo site_url('index.php/salesvisit/selectdata'); ?>',{id:id}, function (rs) { console.log(rs)
            
            var form_data = $('#searchForm').serialize();
            var returnvalid = checkvalid();
            if (returnvalid === false) {
                return false;
            }
            $.messager.progress();

            // var userid = $("#sales").combogrid('getValue');
            var userid =  $("#sales").combogrid('getValues');
            userid.join(',');

            var salesGrid = $('#sales').combogrid('grid');  // get datagrid object
            var salesRow = salesGrid.select();  // get the selected row
            var username = rs.first_name + ' ' + rs.last_name;
            var area = rs.area;
            var department = rs.section;
            var accountid = $("#account_id").val();
            var description = $("#objective").combogrid('getValue');
            var report_type = $("#report_type").combogrid('getValue');
            var date_start = $("#date_from").datebox('getValue');
            var due_date = $("#date_to").datebox('getValue');
            var datestartSplit = date_start.split('/');
            var sendDateStart = datestartSplit[2] + '-' + datestartSplit[1] + '-' + datestartSplit[0];
            var datestopSplit = due_date.split('/');
            var sendDateStop = datestopSplit[2] + '-' + datestopSplit[1] + '-' + datestopSplit[0];

            var roleid =  $("#sales_team").combogrid('getValues');
            roleid.join(',');

            var url = '<?php echo REPORT_VIEWER_URL;?>rpt_monthly_report.rptdesign&__showtitle=false&userid=' + userid
                + '&area=' + area + '&accountid=' + accountid + '&department=' + department + '&description=' + description
                + '&report_type=' + report_type + '&date_start=' + sendDateStart + '&due_date=' + sendDateStop + '&username=' + username+'&roleid='+roleid;
            LoadURL(url, "birt-result");
            $.messager.progress('close');
        }, 'json');
    }

    function Filter() {
        var form_data = $('#searchForm').serialize();
        var returnvalid = checkvalid();

        if (returnvalid === false) {
            return false;
        }
        $.messager.progress();

        // var userid = $("#sales").combogrid('getValue');
        var userid =  $("#sales").combogrid('getValues');
            userid.join(',');

        var salesGrid = $('#sales').combogrid('grid');  // get datagrid object
        var salesRow = salesGrid.select();  // get the selected row
        var username = salesRow.first_name + ' ' + salesRow.last_name;
        var area = salesRow.area;
        var department = salesRow.section;

        var accountid = $("#account_id").val();

        var description = $("#objective").combogrid('getValue');

        var report_type = $("#report_type").combogrid('getValue');
        var date_start = $("#date_from").datebox('getValue');
        var due_date = $("#date_to").datebox('getValue');

        var datestartSplit = date_start.split('/');
        var sendDateStart = datestartSplit[2] + '-' + datestartSplit[1] + '-' + datestartSplit[0];

        var datestopSplit = due_date.split('/');
        var sendDateStop = datestopSplit[2] + '-' + datestopSplit[1] + '-' + datestopSplit[0];

        var roleid =  $("#sales_team").combogrid('getValues');
            roleid.join(',');

        var url = '<?php echo REPORT_VIEWER_URL;?>rpt_monthly_report.rptdesign&__showtitle=false&userid=' + userid
            + '&area=' + area + '&accountid=' + accountid + '&department=' + department + '&description=' + description
            + '&report_type=' + report_type + '&date_start=' + sendDateStart + '&due_date=' + sendDateStop + '&username=' + username+'&roleid='+roleid;
        console.log(url)
        LoadURL(url, "birt-result");

        $.messager.progress('close');
    }

    function cancel_report() {
        $('#dialog').dialog('close')
    }

    function cancel_report_month() {
        $('#dialog_month').dialog('close')
    }

    function cancel_report_weekly() {
        $('#dialog_weekly').dialog('close')
    }

    function Send_report() {
        var msg = 'Send Report'; console.log(msg)
        $('#dialog').window({
            title: msg,
            width: 900,
            height: 300,
            position: 'center',
            resizable: false,
            closed: false,
            cache: false,
            modal: true,
        });
        $("#dialog").css("display", "block");
    }

    function Send_weekly_manual() {
        var msg = 'Send Report';
        $('#dialog_weekly').window({
            title: msg,
            width: 900,
            height: 300,
            position: 'center',
            resizable: false,
            closed: false,
            cache: false,
            modal: true,
        });
        $("#dialog_weekly").css("display", "block");
    }

    function Send_month_manual() {
        var msg = 'Send Report';
        $('#dialog_month').window({
            title: msg,
            width: 900,
            height: 300,
            position: 'center',
            resizable: false,
            closed: false,
            cache: false,
            modal: true,
        });
        $("#dialog_month").css("display", "block");
    }//function

    function checkval_send() {
        if ($("#report_type_model").combogrid('getValue') == '') {
            $('#report_type_model').next().find('input').focus();
            return false;
        
        } else if ($("#sales_report").combogrid('getValue') == '') {
            $('#sales_report').next().find('input').focus();
            return false;
        
        } else if ($("#report_type_model").combogrid('getValue') == 'Weekly Plan' && $("#weekly_report").combogrid('getValue') == '') {
            $('#weekly_report').next().find('input').focus();
            return false;

        } else if ($("#report_type_model").combogrid('getValue') == 'Monthly Plan' && $("#monthly_report").combogrid('getValue') == '') {
            $('#monthly_report').next().find('input').focus();
            return false;

        } else if ($("#send_to_user").combogrid('getValues') == '') {
            $('#send_to_user').next().find('input').focus();
            return false;
        } else {
            return true;
        }
    }

    function Send(send) {
        $(send).prop("onclick", null).off("click");
        var id = $("#sales_report").combogrid('getValue');
        var returnvalid = checkval_send();
        
        if (returnvalid === false) {
            // $.messager.progress('close');
            $(send).click(function(){
                Send(send);
            });
            return false;
        }

        $.post('<?php echo site_url('index.php/salesvisit/selectdata'); ?>',{id:id}, function (rs) {

        $.messager.progress();

        var form_data = $('#sendForm').serialize();
        var url = '<?php echo site_url("salesvisit/sendmail_report")?>';
        var salesGrid = $('#sales_report').combogrid('grid');   // get datagrid object
        var salesRow = salesGrid.select();  // get the selected row
        var username = rs.first_name + ' ' + rs.last_name;

        var area = rs.area;
        var department = rs.section;

        var a_report = $("#report_type_model").combogrid('getValue');
        var plan = '';
        if (a_report == 'Weekly Plan') {
            plan = $("#weekly_report").combogrid('getValue');

            var weekly_report = $('#weekly_report').combogrid('grid');  // get datagrid object
            var weekly = weekly_report.datagrid('getSelected'); // get the selected row
            var year = weekly.weekly_year;

        } else if (a_report == 'Monthly Plan') {
            plan = $("#monthly_report").combogrid('getValue');

            var monthly_report = $('#monthly_report').combogrid('grid');    // get datagrid object
            var monthly = monthly_report.datagrid('getSelected');   // get the selected row
            var year = monthly.monthly_year;
        }


        var form_data = {
            userid: $("#sales_report").combogrid('getValue'),
            area: area,
            year: year,
            department: department,
            report_plan: plan,
            report_type: $("#report_type_model").combogrid('getValue'),
            date_start: $("#date_from_report").datebox('getValue'),
            due_date: $("#date_to_report").datebox('getValue'),
            objective: '',
            submodule: 'weeklyplan',
            send_to: $("#send_to_user").combogrid('getValues'),
            username: username
        };

        $.ajax(url, {
            type: 'POST',
            // dataType: 'json',
            data: form_data,
            success: function (data) { //console.log(data); return false;
                var obj = JSON.parse(data);
                // var obj = data;
                $.messager.progress('close');
                if (obj.status == true) {
                    var errMsg = obj.msg + " " + obj.error;
                } else {
                    if (obj.error != '') {
                        var errMsg = obj.msg + " error: " + obj.error;
                    } else {
                        var errMsg = obj.msg + " " + obj.error;
                    }
                }
                $.messager.alert('Info', errMsg, 'info', function () {
                    if (obj.status == true) {
                        //window.opener.location.reload();
                        window.close();

                    }
                    else {
                        console.log(obj);
                    }
                });
                $(send).click(function(){
                    Send(send);
                });
            },
            error: function (data) {
                $.messager.progress('close');
                $.messager.alert('Retrieve data', data, 'error');
            }
        });
        }, 'json');
    }



    function Send_weekly(send) {

        if ($("#weekly_report_admin").combogrid('getValue') == '') {
            $('#weekly_report_admin').next().find('input').focus();
            return false;
        }
        $(send).prop("onclick", null).off("click");
        $.messager.progress();
        var url = '<?php echo $site_URL . "_Auto_Script/send_email_Weely_Plan_Weekly_Report.php" ?>';
        var date_from = $("#date_from_admin_weekly").combogrid('getValue');
        var datestartSplit = date_from.split('/');
        var sendDateStart = datestartSplit[2] + '-' + datestartSplit[1] + '-' + datestartSplit[0];

        var section = $("#section_weekly").combogrid('getValue');

        var form_data = {
            runtype: '2',
            section: section,
            date_time: sendDateStart
        };

        $.ajax(url, {
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success: function (data) {
                console.log('week');
                //var obj = $.parseJSON(data);
                var obj = data;
                $.messager.progress('close');
                if (obj.status == true) {
                    var errMsg = obj.msg + " " + obj.error;
                } else {
                    if (obj.error != '') {
                        var errMsg = obj.msg + " error: " + obj.error;
                    } else {
                        var errMsg = obj.msg + " " + obj.error;
                    }
                }
                $.messager.alert('Info', errMsg, 'info', function () {
                    if (obj.status == true) {
                        //window.opener.location.reload();
                        window.close();

                    }
                    else {
                        console.log(obj);
                    }
                });
                $(send).click(function(){
                    Send_weekly(send);
                });
            },
            error: function (data) {
                $.messager.progress('close');
                $.messager.alert('Retrieve data', data, 'error');
            }
        });

    }


    function Send_month(send) {

        if ($("#monthly_report_admin").combogrid('getValue') == '') {
            $('#monthly_report_admin').next().find('input').focus();
            return false;
        }

        $(send).prop("onclick", null).off("click");
        $.messager.progress();
        var url = '<?php echo $site_URL . "_Auto_Script/send_email_Monthly_Plan.php" ?>';

        var date_from = $("#date_from_report_admin").combogrid('getValue');
        var datestartSplit = date_from.split('/');
        var sendDateStart = datestartSplit[2] + '-' + datestartSplit[1] + '-' + datestartSplit[0];

        var section = $("#section_month").combogrid('getValue');

        var form_data = {
            runtype: '2',
            section: section,
            date_time: sendDateStart
        };

        $.ajax(url, {
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success: function (data) {
                console.log('month');
                //var obj = $.parseJSON(data);
                var obj = data;
                $.messager.progress('close');
                if (obj.status == true) {
                    var errMsg = obj.msg + " " + obj.error;
                } else {
                    if (obj.error != '') {
                        var errMsg = obj.msg + " error: " + obj.error;
                    } else {
                        var errMsg = obj.msg + " " + obj.error;
                    }
                }
                $.messager.alert('Info', errMsg, 'info', function () {
                    if (obj.status == true) {
                        //window.opener.location.reload();
                        window.close();

                    }
                    else {
                        console.log(obj);
                    }
                });
                $(send).click(function(){
                    Send_month(send);
                });
            },
            error: function (data) {
                $.messager.progress('close');
                $.messager.alert('Retrieve data', data, 'error');
            }
        });

    }


</script>