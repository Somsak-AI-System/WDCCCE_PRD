<?
session_start();
include("../config.inc.php");
require_once("../library/dbconfig.php");
require_once("../library/myFunction.php");
//require_once("../library/generate_MYSQL.php");

include_once("../library/myLibrary_mysqli.php");
//include_once("library/genarate.inc.php");
/*global $generate;
$generate = new generate($dbconfig ,"DB");*/
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Address Information</title>
    <link REL="SHORTCUT ICON" HREF="../themes/AICRM.ico">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="js/referal.js"></script> -->
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>

    <script>
        jQuery(document).ready(function() {
            Get_Province();
        });

        function Get_Province(){
            $("#tbm_provinceid").empty();
            $("#tbm_provinceid").append();
            $.ajax({
                url    :'get_province.php',
                type: 'POST',
                cache: false,
                dataType: 'html',
                success: function(data, textStatus, jqXHR){
                    $("#tbm_provinceid").append(data);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.log('ERRORS: ' + textStatus);
                }
            }); //ajax
        }


        function Get_Region(){
            /*var tbm_provinceid = $("#tbm_provinceid").val();
            $.ajax({
                url    :'get_region.php',
                type: 'POST',
                cache: false,
                data: {"tbm_provinceid": tbm_provinceid},
                dataType: 'html',
                success: function(data, textStatus, jqXHR){
                    
                    $("#tbm_regionid").val(data);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.log('ERRORS: ' + textStatus);
                }
            });*/ 
        }


        function Get_Amphur(){
            var tbm_provinceid = $("#tbm_provinceid").val();
            var selected = $("#tbm_provinceid").find('option:selected');
            var tbm_regionid = selected.data('regionid');
            
            $('#tbm_regionid').val(tbm_regionid);
            $("#tbm_amphurid").empty();
            $("#tbm_amphurid").append();
            $.ajax({
                url    :'get_amphur.php',
                type: 'POST',
                cache: false,
                data: {"tbm_provinceid": tbm_provinceid},
                dataType: 'html',
                success: function(data, textStatus, jqXHR){
                    
                    $("#tbm_amphurid").append(data);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.log('ERRORS: ' + textStatus);
                }
            }); //ajax
        }
        function Get_District(){
            var tbm_amphurid = $("#tbm_amphurid").val();
           
            $("#tbm_districtid").empty();
            $("#tbm_districtid").append();
            $.ajax({
                url    :'get_district.php',
                type: 'POST',
                cache: false,
                data: {"tbm_amphurid": tbm_amphurid},
                dataType: 'html',
                success: function(data, textStatus, jqXHR){
                    
                    $("#tbm_districtid").append(data);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.log('ERRORS: ' + textStatus);
                }
            }); //ajax
        }
        function Get_Postcode(){
            var tbm_districtid = $("#tbm_districtid").val();

            $("#tbm_postcode").empty();
            $.ajax({
                url    :'get_postcode.php',
                type: 'POST',
                cache: false,
                data: {"tbm_districtid": tbm_districtid},
                dataType: 'html',
                success: function(data, textStatus, jqXHR){
                    
                    $("#tbm_postcode").append(data);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.log('ERRORS: ' + textStatus);
                }
            }); //ajax
        }
        function pick(){
            
            //window.opener.document.getElementById("<?//=$_REQUEST["f1"]?>//").value = $("#tbm_regionid").val();
            window.opener.document.getElementById("<?=$_REQUEST["f2"]?>").value = $("#tbm_provinceid option:selected").text();
            window.opener.document.getElementById("<?=$_REQUEST["f3"]?>").value = $("#tbm_amphurid option:selected").text();
            window.opener.document.getElementById("<?=$_REQUEST["f4"]?>").value = $("#tbm_districtid option:selected").text();
            window.opener.document.getElementById("<?=$_REQUEST["f5"]?>").value = $("#tbm_postcode option:selected").text();
            window.close();
        }
    </script>

</head>

<body  style="background:#FFFFFF !important">
<div class="wrapper" style="background:#FFFFFF !important; width: 98% !important" >

    <div class="hTopic">
        <!-- <font color="white">&nbsp;&nbsp;Address Information</font> -->
        <label style="font-size: 14px; font-weight: bold; color: #fff">&nbsp;&nbsp;Address Information</label>    
    </div>

    <!-- <table border="0" width="100%" height="auto" align="center" cellpadding="0" cellspacing="3" bgcolor="#FFFFFF"> -->
    <table border="0" width="100%" height="auto" align="center" cellpadding="0" cellspacing="3" bgcolor="#FFFFFF">
        <tr>
            <input type="text" name="tbm_regionid" id="tbm_regionid" hidden='true'>

            <td width="15%" align="left" valign="middle">
                <label class="textLabel30">จังหวัด :</label>
            </td>
            <td>
                <select name="tbm_provinceid" id="tbm_provinceid" class="selectStyle" onchange="Get_Amphur();Get_Region();" style="width:200px">
                </select>
            </td>

            <td  align="left" valign="middle" ><label class="textLabel30">อำเภอ / เขต :</label></td>
            <td  >
                <select name="tbm_amphurid" id="tbm_amphurid" class="selectStyle" onchange="Get_District()" style="width:200px">
                </select>
            </td>
        </tr>
        <tr >
            <td align="left" valign="middle" ><label class="textLabel30">ตำบล / แขวง :</label></td>
            <td  >
                <select name="tbm_districtid" id="tbm_districtid" class="selectStyle" onchange="Get_Postcode()" style="width:200px">
                </select>
            </td>
            <td  align="left" valign="middle" ><label class="textLabel30">รหัสไปรษณีย์ :</label></td>
            <td  >
                <select name="tbm_postcode" id="tbm_postcode" class="selectStyle" style="width:200px">
                </select>
            </td>            
        </tr>

    </table>
    <table border="0" width="100%" height="auto" align="center" cellpadding="0" cellspacing="3" bgcolor="#FFFFFF" style="border-bottom: 1px solid #ddd;">
    <tr>
        <td></td>
        <td align="center"> 
            <!-- <p> <a href="javascript:pick()">OK</a> </p> -->
            
            <button title="OK" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="pick();" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px;    background-color: #018FFB;color: #fff;font-weight: 500;border: none;border: 1px solid #018FFB;padding: 5px 10px;cursor: pointer;margin: 2px;border-radius: 5px;font-family: 'PromptMedium', serif;font-size: 12px;">
                <!-- <img src="../themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
                &nbsp; -->OK
            </button>

        </td>
        <td></td>
    </tr>
    </table>
</div>
</div>
<? //include("include/footer.php");?>
</body>
</html>

<style type="text/css">
    table{
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
</style>