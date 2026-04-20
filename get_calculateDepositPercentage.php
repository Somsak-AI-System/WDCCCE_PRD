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
$crmid = $_REQUEST["crmid"];

$sql_quotes = "SELECT deposit FROM aicrm_quotes WHERE aicrm_quotes.quoteid = '" . $crmid . "'";
$a_data_quotes = $generate->process($sql_quotes, "all");

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
                            <td class="lvtCol" align="left" width="40%">% มัดจำ</td>
                            <td>
                                <select name="deposit" id="deposit" class="small user-success">';
                                    <?php
                                        $sql_deposit = "SELECT * FROM aicrm_deposit ORDER BY deposit ASC";
                                        $data_deposit = $myLibrary_mysqli->select($sql_deposit);
                                        $selected = '';
                                        foreach ($data_deposit as $key => $value) {
                                            if($value['deposit'] == $a_data_quotes[0]["deposit"]){ $selected = 'selected'; }else{$selected = '';}
                                            echo '<option value="' . $value['deposit'] . '" '.$selected.'>' . $value['deposit'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        
                        <tr style="height:25px" bgcolor="white">
                            <td></td>
                            <td>
                                <button type="button" class="btn crmbutton small save" onclick="Save();">Edit % มัดจำ</button>
                                <button type="button" class="btn crmbutton small btnedit" onclick="clearform();">Cancel</button>
                            </td>
                        </tr>
                    </table>

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

    function closeDepositDialog() {
        if (jQuery('#dialog').length) {
            jQuery('#dialog').window('close');
        }
    }

    function clearform(){
        closeDepositDialog();
        jQuery.messager.progress('close');
    }


    function Save() {

        var deposit = jQuery("#deposit").val();
      
        var url = 'edit_calculateDepositPercentage.php';
        var data = [];

        data.push({
            crmid:"<?php echo $crmid;?>",
            deposit: deposit

        });

        if (deposit == '') {
            jQuery(`#deposit`).focus();
        } else {
            closeDepositDialog();

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
</script>