<?php
session_start();
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");
include_once("library/myLibrary_mysqli.php");

global $generate, $myLibrary_mysqli;
$generate = new generate($dbconfig, "DB");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$date = date('Y-m-d');
$smownerid = $_SESSION["authenticated_user_id"];
$crmid = $_REQUEST["crmid"];

$sql_products = "SELECT
    aicrm_products.*
FROM
    aicrm_products
	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid 
WHERE
aicrm_crmentity.deleted = 0
	and aicrm_products.productid = '" . $crmid . "'";
$a_data_products = $generate->process($sql_products, "all");

?>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="asset/css/smoothness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" />
<style>
    .txtBox {
        border: 1px solid #AAA !important;
        border-radius: 1px !important;
    }

    .calendar {
        display: block !important;
    }
</style>

<!-- <div class="mailClientBg">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="moduleName" width="80%" style="padding-left:10px;">Serial</td>
            <td width=30% nowrap class="componentName" align=right>AI-CRM</td>
        </tr>
    </table>
</div> -->

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 100%;" title="" id="divplan">
    <table width="100%" cellpadding="6" cellspacing="0" border="0" class="homePageMatrixHdr">
        <tr>
            <td style="padding:0px;">
                <form id="PopEditView" method="POST" name="PopEditView">
                    <input type="hidden" id="action" name="action" value="" />
                    <input type="hidden" name="crmid" id="crmid" class="txtBox" style="width:210px" value="<?php echo $_REQUEST["crmid"] ?>">


                    <table class="small" style="background-color:#eaeaea;" width="100%" cellspacing="1" cellpadding="5" border="0">
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left" width="40%">Serial No.</td>
                            <td>
                                <input type="text" id="serial_no" name="serial_no" class="txtBox" value="AUTO GEN ON SAVE" readonly style="background-color:#CCC ;">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left" width="40%">Serial Name<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="serial_name" name="serial_name" class="txtBox" value="">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Product Name</td>
                            <td>
                                <input type="text" id="productname" name="productname" class="txtBox" value="<?php echo $a_data_products[0]["productname"]; ?>" readonly style="background-color:#CCC ;">
                                <input type="hidden" id="productid" name="productid" value="<?php echo $a_data_products[0]["productid"]; ?>">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Account Name<span style="color: #FF0000;">*</span></td>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td width="100%">
                                                <input name="account_name" readonly="" id="account_name" type="text" value="" tabindex="" class="txtBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width: 94%">
                                                <input name="account_id" id="account_id" type="hidden" value="">
                                            </td>
                                            <td>
                                                <img tabindex="" src="themes/softed/images/select.gif" alt="Select" title="Select" language="javascript" onclick="return window.open(&quot;index.php?module=Accounts&amp;action=Popup&amp;popuptype=specific_account_address&amp;form=PopEditView&amp;form_submit=false&amp;fromlink=&amp;module_return=Serial&quot;,&quot;test&quot;,&quot;left=500,width=1000,height=602,top=250,resizable=0,scrollbars=0&quot;);" align="absmiddle" style="cursor:hand;cursor:pointer">
                                            </td>
                                            <td>
                                                <input type="image" src="themes/softed/images/clear_field.gif" alt="Clear" title="Clear" language="javascript" onclick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style="cursor:hand;cursor:pointer">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Installation Date<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="serial_date_install" name="serial_date_install" class="txtBox serial_date_install easyui-datebox" value="">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Warranty Active Date<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="warranty_active_date" name="warranty_active_date" class="txtBox warranty_active_date easyui-datebox" value="">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Warranty Expire Date<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="warranty_expired_date" name="warranty_expired_date" class="txtBox warranty_expired_date easyui-datebox" value="">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Frequency of PM (Month)<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="pm_time_no" name="pm_time_no" class="txtBox" value="">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Frequency of Calibration (Month)<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="time_cal_no" name="time_cal_no" class="txtBox" value="">
                            </td>
                        </tr>

                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Number of times for PM<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="around_pm" name="around_pm" class="txtBox" value="">
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Number of times for CAL<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="text" id="around_cal" name="around_cal" class="txtBox" value="">
                            </td>
                        </tr>

                        <tr style="height:25px" bgcolor="white">
                            <td class="lvtCol" align="left">Assigned to<span style="color: #FF0000;">*</span></td>
                            <td>
                                <input type="radio" tabindex="" name="assigntype" checked="" value="U" onclick="toggleAssignTypes(this.value)">&nbsp;User
                                <input type="radio" name="assigntype" value="T" onclick="toggleAssignTypes(this.value)" class="user-success">&nbsp;Group
                                <span id="assign_users" style="display:block">
                                    <select name="assigned_user_id" id="assigned_user_id" class="small user-success">
                                        <?php
                                        $sql_users = "SELECT * FROM aicrm_users WHERE status = 'Active' ORDER BY first_name ASC";
                                        $data_users = $myLibrary_mysqli->select($sql_users);
                                        foreach ($data_users as $key => $value) {
                                            echo '<option value="' . $value['id'] . '">' . $value['first_name'] . " " . $value['last_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </span>

                                <span id="assign_teams" style="display: none;">
                                    <select name="assigned_group_id" id="assigned_group_id" class="small user-success">';
                                        <?php
                                        $sql_groups = "SELECT * FROM aicrm_groups ORDER BY groupname ASC";
                                        $data_groups = $myLibrary_mysqli->select($sql_groups);
                                        foreach ($data_groups as $key => $value) {
                                            echo '<option value="' . $value['groupid'] . '">' . $value['groupname'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr style="height:25px" bgcolor="white">
                            <td></td>
                            <td>
                                <button type="button" class="btn crmbutton small save" onclick="SaveSerial();">Create Serial</button>
                                <button type="button" class="btn crmbutton small btnedit" onclick="clearform();">Cancel</button>
                            </td>
                        </tr>
                    </table>

                    <!-- <div style="text-align: right">
                        <button type="button" class="btn" onclick="genarate_pm();">Genarate</button>
                    </div>

                    <div>

                    </div>

                    <div id="p_after_genarate">
                        <table id="tHeader" class="small" style="background-color:#eaeaea;" width="100%" cellspacing="1"
                               cellpadding="3" border="0">
                            <input type="hidden" id="warranty_active_date_temp" name="warranty_active_date_temp"
                                   value=""/>
                            <input type="hidden" id="warranty_expired_date_temp" name="warranty_expired_date_temp"
                                   value=""/>
                            <input type="hidden" id="time_cal_no_temp" name="time_cal_no_temp" value=""/>
                            <input type="hidden" id="around_cal_temp" name="around_cal_temp" value=""/>
                            <input type="hidden" id="datenow_temp" name="datenow_temp" value=""/>
                            <input type="hidden" id="task_temp" name="task_temp" value=""/>
                        </table>
                        <div id="p_action" style="text-align: right">
                            <div class="div_input">
                                <button type="button" class="btn" onclick="SavePlan();">Save</button>
                                <button type="button" class="btn" onclick="clearform();">Cancel</button>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                    </div> -->

                </form>
            </td>
        </tr>
    </table>
</div>


<script>
    var tempTable = {
        items: []
    };
    var termlist = {};
    jQuery(document).ready(function() {

        // clearform();

        // jQuery(".installation_date").datepicker({
        //     showOn: "button",
        //     buttonImage: "themes/softed/images/btnL3Calendar.gif",
        //     buttonImageOnly: true,
        //     buttonText: "Select date",
        //     dateFormat: "dd-mm-yy"
        // }).datepicker();

        // jQuery(".warranty_active_date").datepicker({
        //     showOn: "button",
        //     buttonImage: "themes/softed/images/btnL3Calendar.gif",
        //     buttonImageOnly: true,
        //     buttonText: "Select date",
        //     dateFormat: "dd-mm-yy"
        // }).datepicker();

        // jQuery(".warranty_expired_date").datepicker({
        //     showOn: "button",
        //     buttonImage: "themes/softed/images/btnL3Calendar.gif",
        //     buttonImageOnly: true,
        //     buttonText: "Select date",
        //     dateFormat: "dd-mm-yy"
        // }).datepicker();

    });


    // function genarate_pm() {

    //     jQuery.messager.progress({
    //         title: 'Please wait',
    //         msg: 'Genarate Plan...',
    //         text: 'PROCESSING'
    //     });
    //     var serialid = jQuery("#serialid").val();
    //     var serial_no = jQuery("#serial_no").text();
    //     var datenow = jQuery("#datenow").val();
    //     var warranty_active_date = jQuery("#warranty_active_date").val();
    //     var warranty_expired_date = jQuery("#warranty_expired_date").val();
    //     var time_cal_no = jQuery("#time_cal_no").val();
    //     var around_cal = jQuery("#around_cal").val();
    //     var task = $("#task").text();

    //     jQuery("#warranty_active_date_temp").val(warranty_active_date);
    //     jQuery("#warranty_expired_date_temp").val(warranty_expired_date);
    //     jQuery("#time_cal_no_temp").val(time_cal_no);
    //     jQuery("#around_cal_temp").val(around_cal);
    //     jQuery("#datenow_temp").val(datenow);
    //     jQuery("#task_temp").val(task);

    //     var form_data = {
    //         serialid: serialid,
    //         serial_no: serial_no,
    //         datenow: datenow,
    //         warranty_active_date: warranty_active_date,
    //         warranty_expired_date: warranty_expired_date,
    //         time_cal_no: time_cal_no,
    //         around_cal: around_cal,
    //         task: task
    //     };
    //     var url = "genarate_plan_cal.php";

    //     jQuery.ajax(url, {
    //         type: 'POST',
    //         dataType: 'json',
    //         data: form_data,
    //         success: function (termdata) {
    //             jQuery.messager.progress('close');
    //             // console.log(termdata);
    //             /*** Set Value ***/
    //             if (termdata.total >0){
    //                 jQuery("#p_after_genarate").show();
    //                 termlist = termdata;
    //                 var term = {rows: []};
    //                 term.rows.push(termdata.rows);
    //                 tempTable.items.push(termdata.rows);
    //                 gentermtable(term);

    //             } else{
    //                 jQuery.messager.alert('Warning', 'No Data', 'error');
    //                 clearform();
    //             }


    //         },
    //         error: function (msg) {

    //         }
    //     });

    // }

    // function gentermtable(termdata) {
    //     // console.log(termdata.rows[0]);
    //     jQuery('#tHeader').datagrid({
    //         data: termdata.rows[0],
    //         contentType: "application/json;charset=utf-8 ",
    //         remoteSort: 'false',
    //         showFooter: 'false',
    //         singleSelect: 'true',
    //         fitColumns: 'false',
    //         striped: 'true',
    //         dataType: 'json',
    //         columns: [[ //Un Fix Columns
    //             {field: 'serialid', hidden: true},
    //             {field: 'no', title: 'No.', halign: 'center', align: 'center', width: '10%'},
    //             {field: 'serial_no', title: 'Serial', halign: 'center', align: 'center', width: '15%'},
    //             {field: 'warranty_active_date', title: 'Plan Date', halign: 'center', align: 'center', width: '20%'},
    //             {field: 'start_time', title: 'Start Time', halign: 'center', align: 'center', width: '15%'},
    //             {field: 'end_time', title: 'End Time', halign: 'center', align: 'center', width: '15%'},
    //             {field: 'datanow', title: 'Update Date', halign: 'center', align: 'center', width: '15%'},
    //             {field: 'task', title: 'Task', halign: 'center', align: 'center', width: '15%'},

    //         ]]
    //     });

    // }

    // function clearform() {

    //     jQuery("#p_after_genarate").hide();

    // }

    jQuery("#assigned_user_id").val('<?php echo $_SESSION['user_id']; ?>');

    function SaveSerial() {

        var serial_no = jQuery("#serial_no").val();
        var serial_name = jQuery("#serial_name").val();
        var productid = jQuery("#productid").val();
        var productname = jQuery("#productname").val();
        var accountid = jQuery("#account_id").val();
        var account_name = jQuery("#account_name").val();
        var serial_date_install = jQuery(`#serial_date_install`).combobox('getValue');
        var warranty_active_date = jQuery(`#warranty_active_date`).combobox('getValue');
        var warranty_expired_date = jQuery(`#warranty_expired_date`).combobox('getValue');
        var pm_time_no = jQuery(`#pm_time_no`).numberbox('getValue');
        var time_cal_no = jQuery(`#time_cal_no`).numberbox('getValue');
        var around_pm = jQuery(`#around_pm`).numberbox('getValue');
        var around_cal = jQuery(`#around_cal`).numberbox('getValue');

        var assigntype = jQuery("#assigntype:checked").val();
        var assigned_user_id = jQuery("#assigned_user_id option:selected").val();
        var assigned_group_id = jQuery("#assigned_group_id option:selected").val();

        var url = 'genarate_save_serial.php';
        var data = [];

        data.push({
            serial_no: serial_no,
            serial_name: serial_name,
            productid: productid,
            accountid: accountid,
            serial_date_install: serial_date_install,
            warranty_active_date: warranty_active_date,
            warranty_expired_date: warranty_expired_date,
            pm_time_no: pm_time_no,
            time_cal_no: time_cal_no,
            around_pm: around_pm,
            around_cal: around_cal,
            assigntype: assigntype,
            assigned_user_id: assigned_user_id,
            assigned_group_id: assigned_group_id,
        });

        if (serial_name == '') {
            jQuery(`#serial_name`).focus();
        }else if(account_name == ''){
            jQuery(`#account_name`).focus();
        }else if(serial_date_install == ''){
            jQuery(`#serial_date_install`).textbox('textbox').focus();
        }else if(warranty_active_date == ''){
            jQuery(`#warranty_active_date`).textbox('textbox').focus();
        }else if(warranty_expired_date == ''){
            jQuery(`#warranty_expired_date`).textbox('textbox').focus();
        }else if(pm_time_no == ''){
            jQuery(`#pm_time_no`).textbox('textbox').focus();
        }else if(time_cal_no == ''){
            jQuery(`#time_cal_no`).textbox('textbox').focus();
        }else if(around_pm == ''){
            jQuery(`#around_pm`).textbox('textbox').focus();
        }else if(around_cal == ''){
            jQuery(`#around_cal`).textbox('textbox').focus();
        } else {

            jQuery.messager.progress({
                title: 'Please wait',
                msg: 'Saving data...',
                text: 'PROCESSING'
            });

            jQuery.ajax({
                type: "POST",
                url: url,
                cache: false,
                data: {
                    data: data
                },
                dataType: "json",
                success: function(returndate) {
                        jQuery.messager.progress('close');
                        if (returndate['status'] == true) {
                            location.reload();
                        }

                    } //success
                    ,
                error: function(err) {
                    jQuery.messager.progress('close');
                    jQuery.messager.alert('Error', err, 'error');
                }
            });

        }

        //     //console.log(json);
        //     //return false;

    }
    jQuery(`#pm_time_no`).numberbox({
        min: 0,
        precision: 2,
        groupSeparator: ',',
        width: '87%'
    });
    jQuery(`#time_cal_no`).numberbox({
        min: 0,
        precision: 2,
        groupSeparator: ',',
        width: '87%'
    });
    jQuery(`#around_pm`).numberbox({
        min: 0,
        precision: 2,
        groupSeparator: ',',
        width: '87%'
    });
    jQuery(`#around_cal`).numberbox({
        min: 0,
        precision: 2,
        groupSeparator: ',',
        width: '87%'
    });
    jQuery(`#serial_date_install`).datebox({
        width: '87%',
        formatter: function(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate();
            return (d < 10 ? ('0' + d) : d) + '-' + (m < 10 ? ('0' + m) : m) + '-' + y;
        },
        parser: function(s) {
            if (!s) return new Date();
            var ss = s.split('\-');
            var d = parseInt(ss[0], 10);
            var m = parseInt(ss[1], 10);
            var y = parseInt(ss[2], 10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                return new Date(y, m - 1, d);
            } else {
                return new Date();
            }
        }
    });
    jQuery(`#warranty_active_date`).datebox({
        width: '87%',
        formatter: function(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate();
            return (d < 10 ? ('0' + d) : d) + '-' + (m < 10 ? ('0' + m) : m) + '-' + y;
        },
        parser: function(s) {
            if (!s) return new Date();
            var ss = s.split('\-');
            var d = parseInt(ss[0], 10);
            var m = parseInt(ss[1], 10);
            var y = parseInt(ss[2], 10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                return new Date(y, m - 1, d);
            } else {
                return new Date();
            }
        }
    });
    jQuery(`#warranty_expired_date`).datebox({
        width: '87%',
        formatter: function(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate();
            return (d < 10 ? ('0' + d) : d) + '-' + (m < 10 ? ('0' + m) : m) + '-' + y;
        },
        parser: function(s) {
            if (!s) return new Date();
            var ss = s.split('\-');
            var d = parseInt(ss[0], 10);
            var m = parseInt(ss[1], 10);
            var y = parseInt(ss[2], 10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                return new Date(y, m - 1, d);
            } else {
                return new Date();
            }
        }
    });

    function toggleAssignTypes(currTypes) {
        if (currTypes == "U") {
            document.getElementById("assign_users").style.display = 'block';
            document.getElementById("assign_teams").style.display = 'none';
        } else {
            document.getElementById("assign_teams").style.display = 'block';
            document.getElementById("assign_users").style.display = 'none';
        }
    }
</script>